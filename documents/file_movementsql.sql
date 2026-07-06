2. Each Officer's Screen View
A. Dealing Assistant (DA) Screen
sql
-- --------------------------------------------------------
-- DA Dashboard Query - Pending Applications
-- --------------------------------------------------------
SELECT 
    a.id,
    a.application_no,
    a.application_type,
    al.allottee_name,
    al.allottee_name_hindi,
    al.property_number,
    al.allotment_no,
    a.created_date,
    DATE_FORMAT(a.created_date, '%d-%b-%Y %H:%i') as created_date_formatted,
    DATEDIFF(NOW(), a.created_date) as days_pending,
    CASE 
        WHEN DATEDIFF(NOW(), a.created_date) <= 3 THEN 'Normal'
        WHEN DATEDIFF(NOW(), a.created_date) <= 7 THEN 'Urgent'
        ELSE 'Overdue'
    END as priority,
    COUNT(am.id) as total_movements,
    (SELECT remarks FROM application_notes WHERE application_id = a.id ORDER BY created_at DESC LIMIT 1) as last_remark
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
LEFT JOIN application_movements am ON a.id = am.application_id
WHERE a.current_step_id = (
    SELECT id FROM workflow_steps 
    WHERE workflow_id = @allotment_workflow_id 
    AND step_code = 'allotment-da-review'
)
AND a.status IN ('pending', 'in_progress', 'forwarded')
GROUP BY a.id
ORDER BY a.created_date ASC;

-- --------------------------------------------------------
-- DA - Application Details View
-- --------------------------------------------------------
SELECT 
    a.*,
    al.*,
    ws.step_name as current_step,
    u.name as current_officer,
    r.name as current_role,
    w.name as workflow_name
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
JOIN workflow_steps ws ON a.current_step_id = ws.id
JOIN users u ON a.current_user_id = u.id
JOIN roles r ON a.current_role_id = r.id
JOIN workflows w ON a.workflow_id = w.id
WHERE a.id = 1;  -- Replace with application ID

-- --------------------------------------------------------
-- DA - Check Required Documents
-- --------------------------------------------------------
SELECT 
    'Lottery Receipt' as document_name,
    CASE WHEN EXISTS(
        SELECT 1 FROM application_documents 
        WHERE application_id = 1 AND document_type = 'lottery_receipt'
    ) THEN 'Uploaded' ELSE 'Pending' END as status
UNION ALL
SELECT 
    'Payment Receipt (10%)',
    CASE WHEN EXISTS(
        SELECT 1 FROM application_documents 
        WHERE application_id = 1 AND document_type = 'payment_receipt'
    ) THEN 'Uploaded' ELSE 'Pending' END
UNION ALL
SELECT 
    'Identity Proof (Aadhar)',
    CASE WHEN EXISTS(
        SELECT 1 FROM application_documents 
        WHERE application_id = 1 AND document_type = 'identity_proof'
    ) THEN 'Uploaded' ELSE 'Pending' END
UNION ALL
SELECT 
    'Pan Card',
    CASE WHEN EXISTS(
        SELECT 1 FROM application_documents 
        WHERE application_id = 1 AND document_type = 'pan_card'
    ) THEN 'Uploaded' ELSE 'Pending' END
UNION ALL
SELECT 
    'Property Allotment Form',
    CASE WHEN EXISTS(
        SELECT 1 FROM application_documents 
        WHERE application_id = 1 AND document_type = 'allotment_form'
    ) THEN 'Uploaded' ELSE 'Pending' END;
B. Office Superintendent (OS) Screen
sql
-- --------------------------------------------------------
-- OS Dashboard - Applications for Verification
-- --------------------------------------------------------
SELECT 
    a.id,
    a.application_no,
    al.allottee_name,
    al.property_number,
    al.allotment_no,
    a.created_date,
    -- Get DA's verification remarks
    (SELECT remarks FROM application_notes 
     WHERE application_id = a.id AND role_id = 1 
     ORDER BY created_at DESC LIMIT 1) as da_remarks,
    -- Check if DA has recommended
    (SELECT COUNT(*) FROM application_notes 
     WHERE application_id = a.id AND role_id = 1 
     AND note_type = 'recommendation') as da_recommended,
    -- Get all documents status
    (SELECT COUNT(*) FROM application_documents 
     WHERE application_id = a.id) as total_documents,
    -- Get movement history
    (SELECT COUNT(*) FROM application_movements 
     WHERE application_id = a.id) as total_movements
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
WHERE a.current_step_id = (
    SELECT id FROM workflow_steps 
    WHERE workflow_id = @allotment_workflow_id 
    AND step_code = 'allotment-os-review'
)
AND a.status IN ('pending', 'in_progress', 'forwarded')
ORDER BY a.created_date ASC;

-- --------------------------------------------------------
-- OS - Complete Noting History
-- --------------------------------------------------------
SELECT 
    an.id,
    u.name as officer_name,
    r.name as role_name,
    r.short_name as role_short,
    an.note_type,
    an.remarks,
    an.signature_date,
    an.signature,
    CASE 
        WHEN an.signature IS NOT NULL THEN '✓ Signed'
        ELSE '✗ Not Signed'
    END as signature_status,
    DATE_FORMAT(an.created_at, '%d-%b-%Y %H:%i') as noted_at
FROM application_notes an
JOIN users u ON an.user_id = u.id
JOIN roles r ON an.role_id = r.id
WHERE an.application_id = 1  -- Replace with application ID
ORDER BY an.created_at ASC;
C. Estate Officer (EO) Screen
sql
-- --------------------------------------------------------
-- EO Dashboard - Final Approval Pending
-- --------------------------------------------------------
SELECT 
    a.id,
    a.application_no,
    al.allottee_name,
    al.allottee_name_hindi,
    al.property_number,
    al.allotment_no,
    a.created_date,
    -- OS verification status
    (SELECT COUNT(*) FROM application_notes 
     WHERE application_id = a.id AND role_id = 2 
     AND note_type IN ('verification', 'recommendation')) as os_verified,
    -- DA verification status
    (SELECT COUNT(*) FROM application_notes 
     WHERE application_id = a.id AND role_id = 1 
     AND note_type = 'recommendation') as da_recommended,
    -- Total time taken so far
    TIMESTAMPDIFF(HOUR, a.created_date, NOW()) as total_hours,
    -- All officers notes summary
    (SELECT GROUP_CONCAT(CONCAT(r.short_name, ': ', LEFT(an.remarks, 30)) SEPARATOR ' | ')
     FROM application_notes an
     JOIN roles r ON an.role_id = r.id
     WHERE an.application_id = a.id
     ORDER BY an.created_at DESC LIMIT 3) as recent_notes
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
WHERE a.current_step_id = (
    SELECT id FROM workflow_steps 
    WHERE workflow_id = @allotment_workflow_id 
    AND step_code = 'allotment-eo-review'
)
AND a.status IN ('pending', 'in_progress', 'forwarded')
ORDER BY a.created_date ASC;

-- --------------------------------------------------------
-- EO - Complete Application Summary for Decision
-- --------------------------------------------------------
SELECT 
    'Application Details' as section,
    a.application_no as value1,
    a.application_type as value2,
    a.created_date as value3
FROM applications a
WHERE a.id = 1
UNION ALL
SELECT 
    'Allottee Details',
    al.allottee_name,
    al.aadhar_card_number,
    al.pan_card_number
FROM allottees al
JOIN applications a ON a.allottee_id = al.id
WHERE a.id = 1
UNION ALL
SELECT 
    'Property Details',
    al.property_number,
    al.prefix,
    al.quarter_id
FROM allottees al
JOIN applications a ON a.allottee_id = al.id
WHERE a.id = 1
UNION ALL
SELECT 
    'Recommendation Summary',
    CONCAT('DA: ', (SELECT remarks FROM application_notes 
           WHERE application_id = 1 AND role_id = 1 
           ORDER BY created_at DESC LIMIT 1)),
    CONCAT('OS: ', (SELECT remarks FROM application_notes 
           WHERE application_id = 1 AND role_id = 2 
           ORDER BY created_at DESC LIMIT 1)),
    'Pending for final approval'
FROM dual;









================================================================================================================








-- --------------------------------------------------------
-- Complete Allotment Application Creation Procedure
-- --------------------------------------------------------
DELIMITER //
CREATE PROCEDURE `create_allotment_application`(
    IN p_allottee_id BIGINT,
    IN p_property_id BIGINT,
    IN p_created_by BIGINT,
    IN p_payment_receipt VARCHAR(255),
    IN p_documents JSON
)
BEGIN
    DECLARE v_application_id BIGINT;
    DECLARE v_application_no VARCHAR(50);
    DECLARE v_workflow_id BIGINT;
    DECLARE v_first_step_id BIGINT;
    DECLARE v_first_step_role_id BIGINT;
    DECLARE v_current_year YEAR;
    DECLARE v_operator_id BIGINT;
    
    -- Start transaction
    START TRANSACTION;
    
    -- Get current year
    SET v_current_year = YEAR(CURDATE());
    
    -- Get workflow ID
    SELECT id INTO v_workflow_id FROM workflows WHERE application_type = 'allotment' AND is_active = 1 LIMIT 1;
    
    -- Get first step (Operator)
    SELECT id, role_id INTO v_first_step_id, v_first_step_role_id 
    FROM workflow_steps 
    WHERE workflow_id = v_workflow_id 
    AND is_starting_step = 1 LIMIT 1;
    
    -- Get operator user for first step
    SELECT id INTO v_operator_id FROM users WHERE role_id = v_first_step_role_id AND status = 1 LIMIT 1;
    
    -- Generate application number
    SET v_application_no = CONCAT(
        'APL-',
        v_current_year,
        '-',
        LPAD(
            (SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(application_no, '-', -1) AS UNSIGNED)), 0) + 1 
             FROM applications 
             WHERE application_type = 'allotment' 
             AND YEAR(created_date) = v_current_year), 
            6, '0'
        )
    );
    
    -- Create application
    INSERT INTO applications (
        application_no,
        application_type,
        allottee_id,
        property_id,
        workflow_id,
        current_step_id,
        current_user_id,
        current_role_id,
        status,
        created_by,
        created_date
    ) VALUES (
        v_application_no,
        'allotment',
        p_allottee_id,
        p_property_id,
        v_workflow_id,
        v_first_step_id,
        v_operator_id,
        v_first_step_role_id,
        'pending',
        p_created_by,
        NOW()
    );
    
    SET v_application_id = LAST_INSERT_ID();
    
    -- Insert initial movement
    INSERT INTO application_movements (
        application_id,
        to_user_id,
        to_role_id,
        to_step_id,
        action_type,
        status,
        movement_date,
        remarks
    ) VALUES (
        v_application_id,
        v_operator_id,
        v_first_step_role_id,
        v_first_step_id,
        'created',
        'pending',
        NOW(),
        'Application created by operator'
    );
    
    -- Insert documents if provided
    IF p_documents IS NOT NULL THEN
        INSERT INTO application_documents (
            application_id,
            document_type,
            document_name,
            file_name,
            file_path,
            uploaded_by,
            uploaded_at
        )
        SELECT 
            v_application_id,
            JSON_UNQUOTE(JSON_EXTRACT(doc, '$.type')),
            JSON_UNQUOTE(JSON_EXTRACT(doc, '$.name')),
            JSON_UNQUOTE(JSON_EXTRACT(doc, '$.file_name')),
            JSON_UNQUOTE(JSON_EXTRACT(doc, '$.file_path')),
            p_created_by,
            NOW()
        FROM JSON_TABLE(p_documents, '$[*]' COLUMNS(
            doc JSON PATH '$'
        )) AS docs;
    END IF;
    
    -- Create notification for operator
    INSERT INTO notifications (
        application_id,
        user_id,
        notification_type,
        subject,
        message,
        link
    ) VALUES (
        v_application_id,
        v_operator_id,
        'application_created',
        CONCAT('New Allotment Application: ', v_application_no),
        CONCAT('A new allotment application ', v_application_no, ' has been created for allottee ID: ', p_allottee_id),
        CONCAT('/applications/view/', v_application_id)
    );
    
    -- Log audit trail
    INSERT INTO application_audit_trails (
        application_id,
        user_id,
        role_id,
        action,
        module,
        description,
        new_data
    ) VALUES (
        v_application_id,
        p_created_by,
        (SELECT role_id FROM users WHERE id = p_created_by),
        'create',
        'application',
        CONCAT('Allotment application created: ', v_application_no),
        JSON_OBJECT('application_id', v_application_id, 'allottee_id', p_allottee_id)
    );
    
    COMMIT;
    
    -- Return application ID
    SELECT v_application_id as application_id, v_application_no as application_no;
END//
DELIMITER ;

-- --------------------------------------------------------
-- Forward Allotment Application to Next Officer
-- --------------------------------------------------------
DELIMITER //
CREATE PROCEDURE `forward_allotment_application`(
    IN p_application_id BIGINT,
    IN p_from_user_id BIGINT,
    IN p_action_type VARCHAR(50),
    IN p_remarks TEXT,
    IN p_signature TEXT,
    IN p_uploaded_document JSON
)
BEGIN
    DECLARE v_current_step_id BIGINT;
    DECLARE v_next_step_id BIGINT;
    DECLARE v_to_role_id BIGINT;
    DECLARE v_to_user_id BIGINT;
    DECLARE v_application_no VARCHAR(50);
    DECLARE v_allottee_id BIGINT;
    DECLARE v_movement_id BIGINT;
    
    START TRANSACTION;
    
    -- Get application details
    SELECT 
        current_step_id, 
        application_no, 
        allottee_id 
    INTO 
        v_current_step_id, 
        v_application_no, 
        v_allottee_id 
    FROM applications 
    WHERE id = p_application_id;
    
    -- Get next step
    SELECT 
        ws.id, 
        ws.role_id,
        u.id as to_user_id
    INTO 
        v_next_step_id, 
        v_to_role_id,
        v_to_user_id
    FROM workflow_steps ws
    LEFT JOIN users u ON u.role_id = ws.role_id AND u.status = 1
    WHERE ws.id = (
        SELECT next_step_id FROM workflow_steps WHERE id = v_current_step_id
    )
    LIMIT 1;
    
    -- Insert movement record
    INSERT INTO application_movements (
        application_id,
        from_user_id,
        to_user_id,
        from_role_id,
        to_role_id,
        from_step_id,
        to_step_id,
        action_type,
        remarks,
        status,
        movement_date
    ) VALUES (
        p_application_id,
        p_from_user_id,
        v_to_user_id,
        (SELECT role_id FROM users WHERE id = p_from_user_id),
        v_to_role_id,
        v_current_step_id,
        v_next_step_id,
        p_action_type,
        p_remarks,
        'in_progress',
        NOW()
    );
    
    SET v_movement_id = LAST_INSERT_ID();
    
    -- Insert note with signature
    INSERT INTO application_notes (
        application_id,
        movement_id,
        user_id,
        role_id,
        note_type,
        remarks,
        signature,
        signature_date,
        created_at
    ) VALUES (
        p_application_id,
        v_movement_id,
        p_from_user_id,
        (SELECT role_id FROM users WHERE id = p_from_user_id),
        CASE 
            WHEN p_action_type = 'approved' THEN 'approval'
            WHEN p_action_type = 'rejected' THEN 'rejection'
            WHEN p_action_type = 'send_back' THEN 'send_back'
            WHEN p_action_type = 'recommended' THEN 'recommendation'
            ELSE 'general'
        END,
        p_remarks,
        p_signature,
        NOW(),
        NOW()
    );
    
    -- Update application status
    UPDATE applications 
    SET 
        current_step_id = v_next_step_id,
        current_user_id = v_to_user_id,
        current_role_id = v_to_role_id,
        status = CASE 
            WHEN p_action_type = 'approved' THEN 'approved'
            WHEN p_action_type = 'rejected' THEN 'rejected'
            WHEN p_action_type = 'send_back' THEN 'send_back'
            WHEN p_action_type = 'completed' THEN 'completed'
            ELSE 'in_progress'
        END,
        updated_by = p_from_user_id,
        updated_at = NOW(),
        completed_date = CASE 
            WHEN p_action_type IN ('approved', 'completed') THEN NOW()
            ELSE completed_date
        END
    WHERE id = p_application_id;
    
    -- Upload document if provided
    IF p_uploaded_document IS NOT NULL THEN
        INSERT INTO application_documents (
            application_id,
            movement_id,
            document_type,
            document_name,
            file_name,
            file_path,
            uploaded_by,
            uploaded_at
        ) VALUES (
            p_application_id,
            v_movement_id,
            JSON_UNQUOTE(JSON_EXTRACT(p_uploaded_document, '$.type')),
            JSON_UNQUOTE(JSON_EXTRACT(p_uploaded_document, '$.name')),
            JSON_UNQUOTE(JSON_EXTRACT(p_uploaded_document, '$.file_name')),
            JSON_UNQUOTE(JSON_EXTRACT(p_uploaded_document, '$.file_path')),
            p_from_user_id,
            NOW()
        );
    END IF;
    
    -- Create notification for next officer
    INSERT INTO notifications (
        application_id,
        movement_id,
        user_id,
        notification_type,
        subject,
        message,
        link
    ) VALUES (
        p_application_id,
        v_movement_id,
        v_to_user_id,
        CASE 
            WHEN p_action_type = 'approved' THEN 'application_approved'
            WHEN p_action_type = 'rejected' THEN 'application_rejected'
            WHEN p_action_type = 'send_back' THEN 'application_send_back'
            ELSE 'application_forwarded'
        END,
        CONCAT('Application Update: ', v_application_no),
        CONCAT('Application ', v_application_no, ' has been ', p_action_type, ' with remarks: ', p_remarks),
        CONCAT('/applications/view/', p_application_id)
    );
    
    -- Create notification for allottee
    INSERT INTO notifications (
        application_id,
        user_id,
        notification_type,
        subject,
        message,
        link
    ) VALUES (
        p_application_id,
        (SELECT user_id FROM allottees WHERE id = v_allottee_id),
        'status_change',
        CONCAT('Your application ', v_application_no, ' status updated'),
        CONCAT('Your allotment application status has been updated to: ', 
               CASE 
                   WHEN p_action_type = 'approved' THEN 'Approved'
                   WHEN p_action_type = 'rejected' THEN 'Rejected'
                   WHEN p_action_type = 'send_back' THEN 'Send Back'
                   ELSE 'In Progress'
               END),
        CONCAT('/allottee/applications/view/', p_application_id)
    );
    
    -- Log audit trail
    INSERT INTO application_audit_trails (
        application_id,
        user_id,
        role_id,
        action,
        module,
        description,
        old_data,
        new_data
    ) VALUES (
        p_application_id,
        p_from_user_id,
        (SELECT role_id FROM users WHERE id = p_from_user_id),
        p_action_type,
        'application',
        CONCAT('Application ', p_action_type, ' by officer'),
        JSON_OBJECT('step_id', v_current_step_id, 'status', 'pending'),
        JSON_OBJECT('step_id', v_next_step_id, 'status', p_action_type, 'remarks', p_remarks)
    );
    
    -- If approved, generate allotment letter
    IF p_action_type = 'approved' THEN
        CALL generate_allotment_letter(p_application_id, p_from_user_id);
    END IF;
    
    COMMIT;
END//
DELIMITER ;

-- --------------------------------------------------------
-- Generate Allotment Letter Procedure
-- --------------------------------------------------------
DELIMITER //
CREATE PROCEDURE `generate_allotment_letter`(
    IN p_application_id BIGINT,
    IN p_generated_by BIGINT
)
BEGIN
    DECLARE v_application_no VARCHAR(50);
    DECLARE v_allottee_name VARCHAR(255);
    DECLARE v_property_number VARCHAR(100);
    DECLARE v_allotment_no VARCHAR(100);
    DECLARE v_current_date DATE;
    DECLARE v_pdf_content TEXT;
    DECLARE v_pdf_file_name VARCHAR(255);
    DECLARE v_pdf_path VARCHAR(500);
    
    SET v_current_date = CURDATE();
    
    -- Get application details
    SELECT 
        a.application_no,
        al.allottee_name,
        al.property_number,
        al.allotment_no
    INTO 
        v_application_no,
        v_allottee_name,
        v_property_number,
        v_allotment_no
    FROM applications a
    JOIN allottees al ON a.allottee_id = al.id
    WHERE a.id = p_application_id;
    
    -- Generate PDF file name
    SET v_pdf_file_name = CONCAT('Allotment_Letter_', v_application_no, '_', DATE_FORMAT(v_current_date, '%Y%m%d'), '.pdf');
    SET v_pdf_path = CONCAT('uploads/allotment_letters/', v_pdf_file_name);
    
    -- Generate PDF content (in real implementation, this would use PDF library)
    SET v_pdf_content = CONCAT(
        '====================================\n',
        '    ALLOTMENT LETTER\n',
        '====================================\n\n',
        'Application No: ', v_application_no, '\n',
        'Allotment No: ', v_allotment_no, '\n',
        'Date: ', DATE_FORMAT(v_current_date, '%d-%b-%Y'), '\n\n',
        'To,\n',
        v_allottee_name, '\n\n',
        'Subject: Allotment of Property ', v_property_number, '\n\n',
        'Dear Sir/Madam,\n\n',
        'This is to inform you that the following property has been allotted to you:\n\n',
        'Property Number: ', v_property_number, '\n',
        'Allotment Date: ', DATE_FORMAT(v_current_date, '%d-%b-%Y'), '\n\n',
        'Terms and Conditions:\n',
        '1. The allotment is subject to payment of all dues.\n',
        '2. You are required to sign the agreement within 30 days.\n',
        '3. Possession will be given after execution of agreement.\n\n',
        'Please collect the original letter from the office.\n\n',
        'Yours faithfully,\n\n\n',
        'Estate Officer\n',
        'Jharkhand Housing Board\n',
        '====================================\n',
        'This is a system generated document. No signature required.\n',
        '===================================='
    );
    
    -- Insert PDF record
    INSERT INTO application_pdf_history (
        application_id,
        document_type,
        pdf_file_name,
        pdf_file_path,
        generated_by,
        generated_at,
        pdf_content,
        is_final,
        version
    ) VALUES (
        p_application_id,
        'allotment_letter',
        v_pdf_file_name,
        v_pdf_path,
        p_generated_by,
        NOW(),
        v_pdf_content,
        1,
        1
    );
    
    -- Update application status
    UPDATE applications 
    SET status = 'completed',
        completed_date = NOW()
    WHERE id = p_application_id;
    
    -- Create notification for allottee
    INSERT INTO notifications (
        application_id,
        user_id,
        notification_type,
        subject,
        message,
        link
    ) VALUES (
        p_application_id,
        (SELECT user_id FROM applications a JOIN allottees al ON a.allottee_id = al.id WHERE a.id = p_application_id),
        'application_completed',
        CONCAT('Allotment Letter Generated: ', v_application_no),
        CONCAT('Your allotment letter has been generated. Download from the link below.'),
        CONCAT('/allottee/download/allotment-letter/', p_application_id)
    );
END//
DELIMITER ;










===================================================================================================






-- --------------------------------------------------------
-- Complete Application Tracking with Timeline
-- --------------------------------------------------------
SELECT 
    'Application' as type,
    a.application_no as value,
    a.application_type as category,
    a.status,
    DATE_FORMAT(a.created_date, '%d-%b-%Y %H:%i') as date,
    'Created' as action
FROM applications a
WHERE a.id = 1

UNION ALL

SELECT 
    'Movement' as type,
    CONCAT('Step ', ws.step_order) as value,
    ws.step_name as category,
    am.action_type as status,
    DATE_FORMAT(am.movement_date, '%d-%b-%Y %H:%i') as date,
    CONCAT(
        u.name, 
        ' → ',
        tu.name,
        CASE 
            WHEN am.remarks IS NOT NULL THEN CONCAT(' (', am.remarks, ')')
            ELSE ''
        END
    ) as action
FROM application_movements am
JOIN workflow_steps ws ON am.to_step_id = ws.id
LEFT JOIN users u ON am.from_user_id = u.id
LEFT JOIN users tu ON am.to_user_id = tu.id
WHERE am.application_id = 1
ORDER BY am.movement_date ASC;

-- --------------------------------------------------------
-- Allotment Application Summary Report
-- --------------------------------------------------------
SELECT 
    a.application_no,
    al.allottee_name,
    al.property_number,
    a.created_date,
    a.status,
    ws.step_name as current_step,
    u.name as current_officer,
    r.name as current_role,
    TIMESTAMPDIFF(HOUR, a.created_date, NOW()) as hours_taken,
    COUNT(am.id) as total_movements,
    COUNT(DISTINCT an.id) as total_notes,
    COUNT(DISTINCT ad.id) as total_documents,
    CASE 
        WHEN a.status = 'completed' THEN 'Allotment Complete'
        WHEN a.status = 'approved' THEN 'Approved - Ready for Letter'
        WHEN a.status = 'rejected' THEN 'Rejected'
        WHEN a.status = 'send_back' THEN 'Send Back for Correction'
        ELSE 'In Progress'
    END as application_status
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
LEFT JOIN workflow_steps ws ON a.current_step_id = ws.id
LEFT JOIN users u ON a.current_user_id = u.id
LEFT JOIN roles r ON a.current_role_id = r.id
LEFT JOIN application_movements am ON a.id = am.application_id
LEFT JOIN application_notes an ON a.id = an.application_id
LEFT JOIN application_documents ad ON a.id = ad.application_id
WHERE a.id = 1  -- Replace with application ID
GROUP BY a.id;

-- --------------------------------------------------------
-- Get Allotment Letter PDF History
-- --------------------------------------------------------
SELECT 
    aph.id,
    aph.document_type,
    aph.pdf_file_name,
    aph.pdf_file_path,
    DATE_FORMAT(aph.generated_at, '%d-%b-%Y %H:%i') as generated_at,
    u.name as generated_by,
    aph.version,
    CASE 
        WHEN aph.is_final = 1 THEN 'Final'
        ELSE 'Draft'
    END as version_type
FROM application_pdf_history aph
JOIN users u ON aph.generated_by = u.id
WHERE aph.application_id = 1  -- Replace with application ID
ORDER BY aph.version DESC;


-- --------------------------------------------------------
-- Check All Movements
-- --------------------------------------------------------
SELECT 
    am.id,
    am.action_type,
    am.status,
    from_user.name as from_user,
    to_user.name as to_user,
    from_role.name as from_role,
    to_role.name as to_role,
    am.remarks,
    am.movement_date
FROM application_movements am
LEFT JOIN users from_user ON am.from_user_id = from_user.id
LEFT JOIN users to_user ON am.to_user_id = to_user.id
LEFT JOIN roles from_role ON am.from_role_id = from_role.id
LEFT JOIN roles to_role ON am.to_role_id = to_role.id
WHERE am.application_id = @app_id
ORDER BY am.movement_date;


-- --------------------------------------------------------
-- Check All Notes
-- --------------------------------------------------------
SELECT 
    an.id,
    u.name as officer,
    r.name as role,
    an.note_type,
    an.remarks,
    an.signature,
    an.created_at
FROM application_notes an
JOIN users u ON an.user_id = u.id
JOIN roles r ON an.role_id = r.id
WHERE an.application_id = @app_id
ORDER BY an.created_at;




=================================================================================================




-- --------------------------------------------------------
-- Show Created Application
-- --------------------------------------------------------
SELECT 
    a.id,
    a.application_no,
    a.application_type,
    al.allottee_name,
    al.allottee_name_hindi,
    al.property_number,
    al.allotment_no,
    a.status,
    ws.step_name as current_step,
    u.name as current_officer,
    r.name as current_role,
    a.created_date,
    COUNT(DISTINCT am.id) as total_movements,
    COUNT(DISTINCT an.id) as total_notes,
    COUNT(DISTINCT ad.id) as total_documents
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
LEFT JOIN workflow_steps ws ON a.current_step_id = ws.id
LEFT JOIN users u ON a.current_user_id = u.id
LEFT JOIN roles r ON a.current_role_id = r.id
LEFT JOIN application_movements am ON a.id = am.application_id
LEFT JOIN application_notes an ON a.id = an.application_id
LEFT JOIN application_documents ad ON a.id = ad.application_id
WHERE a.id = @app_id
GROUP BY a.id;
4. Verification Queries After Insert
sql
-- --------------------------------------------------------
-- Check Application Created
-- --------------------------------------------------------
SELECT * FROM applications WHERE id = @app_id;

-- --------------------------------------------------------
-- Check All Movements
-- --------------------------------------------------------
SELECT 
    am.id,
    am.action_type,
    am.status,
    from_user.name as from_user,
    to_user.name as to_user,
    from_role.name as from_role,
    to_role.name as to_role,
    am.remarks,
    am.movement_date
FROM application_movements am
LEFT JOIN users from_user ON am.from_user_id = from_user.id
LEFT JOIN users to_user ON am.to_user_id = to_user.id
LEFT JOIN roles from_role ON am.from_role_id = from_role.id
LEFT JOIN roles to_role ON am.to_role_id = to_role.id
WHERE am.application_id = @app_id
ORDER BY am.movement_date;

-- --------------------------------------------------------
-- Check All Notes
-- --------------------------------------------------------
SELECT 
    an.id,
    u.name as officer,
    r.name as role,
    an.note_type,
    an.remarks,
    an.signature,
    an.created_at
FROM application_notes an
JOIN users u ON an.user_id = u.id
JOIN roles r ON an.role_id = r.id
WHERE an.application_id = @app_id
ORDER BY an.created_at;

-- --------------------------------------------------------
-- Check All Documents
-- --------------------------------------------------------
SELECT 
    ad.id,
    ad.document_type,
    ad.document_name,
    ad.file_name,
    ad.file_path,
    u.name as uploaded_by,
    ad.uploaded_at,
    CASE WHEN ad.is_verified = 1 THEN 'Verified' ELSE 'Not Verified' END as verification_status
FROM application_documents ad
JOIN users u ON ad.uploaded_by = u.id
WHERE ad.application_id = @app_id;

-- --------------------------------------------------------
-- Check Notifications
-- --------------------------------------------------------
SELECT 
    n.id,
    u.name as user,
    n.notification_type,
    n.subject,
    n.message,
    n.is_read,
    n.created_at
FROM notifications n
JOIN users u ON n.user_id = u.id
WHERE n.application_id = @app_id
ORDER BY n.created_at;
5. Application Status Check
sql
-- --------------------------------------------------------
-- Complete Application Status Summary
-- --------------------------------------------------------
SELECT 
    CONCAT('Application No: ', a.application_no) as 'Application Details',
    CONCAT('Allottee: ', al.allottee_name, ' (', al.allottee_name_hindi, ')') as 'Allottee',
    CONCAT('Property: ', al.property_number, ' - ', al.prefix) as 'Property',
    CONCAT('Allotment No: ', al.allotment_no) as 'Allotment',
    CONCAT('Status: ', UPPER(a.status)) as 'Status',
    CONCAT('Current Step: ', ws.step_name) as 'Current Step',
    CONCAT('Current Officer: ', u.name, ' (', r.name, ')') as 'Current Officer',
    CONCAT('Created: ', DATE_FORMAT(a.created_date, '%d-%b-%Y %H:%i')) as 'Created',
    CONCAT('Days Pending: ', DATEDIFF(NOW(), a.created_date), ' days') as 'Pending Duration',
    CONCAT('Total Movements: ', COUNT(DISTINCT am.id)) as 'Total Movements',
    CONCAT('Total Notes: ', COUNT(DISTINCT an.id)) as 'Total Notes',
    CONCAT('Total Documents: ', COUNT(DISTINCT ad.id)) as 'Total Documents'
FROM applications a
JOIN allottees al ON a.allottee_id = al.id
LEFT JOIN workflow_steps ws ON a.current_step_id = ws.id
LEFT JOIN users u ON a.current_user_id = u.id
LEFT JOIN roles r ON a.current_role_id = r.id
LEFT JOIN application_movements am ON a.id = am.application_id
LEFT JOIN application_notes an ON a.id = an.application_id
LEFT JOIN application_documents ad ON a.id = ad.application_id
WHERE a.id = @app_id
GROUP BY a.id;