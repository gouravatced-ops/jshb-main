<?php

namespace App\Enums;

enum AllotteeStepEnum: int
{
    // Step 0: Scheme Selection, Lottery Payment Date, Receipt Image Upload
    case SCHEME_AND_LOTTERY = 0;
    
    // Step 1: Application Number, Date, Personal Details
    case PERSONAL_DETAILS = 1;
    
    // Step 2: Address, Communication Details, Mobile Number
    case COMMUNICATION_DETAILS = 2;
    
    // Step 3: Overview and Final Submit
    case OVERVIEW_AND_SUBMIT = 3;

    /**
     * Get the readable name of the step.
     */
    public function name(): string
    {
        return match($this) {
            self::SCHEME_AND_LOTTERY => 'Scheme Selection & Lottery Payment',
            self::PERSONAL_DETAILS => 'Application & Personal Details',
            self::COMMUNICATION_DETAILS => 'Address & Communication',
            self::OVERVIEW_AND_SUBMIT => 'Overview & Final Submit',
        };
    }
}
