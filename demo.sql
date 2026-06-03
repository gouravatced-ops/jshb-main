CREATE TABLE districts (
    id BIGSERIAL PRIMARY KEY,
    state_id BIGINT NOT NULL,
    name_en VARCHAR(150) NOT NULL,
    name_hi VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE divisions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    division_code VARCHAR(50),
    status INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);

CREATE TABLE otp_logs (
  id SERIAL PRIMARY KEY,
  user_id BIGINT DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  otp_code VARCHAR(255) DEFAULT NULL,
  verified BOOLEAN NOT NULL DEFAULT FALSE,
  purpose VARCHAR(255) NOT NULL DEFAULT 'login',
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_otp_logs_user_id ON otp_logs(user_id);

SELECT setval(pg_get_serial_sequence('login_logs', 'id'), 1);


SELECT
  c.column_name,
  c.data_type,
  c.is_nullable,
  c.column_default,
  tc.constraint_type
FROM information_schema.columns c
LEFT JOIN information_schema.key_column_usage kcu
  ON c.column_name = kcu.column_name
  AND c.table_name = kcu.table_name
LEFT JOIN information_schema.table_constraints tc
  ON tc.constraint_name = kcu.constraint_name
  AND tc.table_name = c.table_name
WHERE c.table_schema = 'public'
  AND c.table_name = 'password_reset_tokens'
ORDER BY c.ordinal_position;