<?php

return [
    'something_wrong' => 'Something went wrong!',
    'unauthorized' => 'Unauthorized',
    'token' => [
        'expired' => 'Your session expired.Please Login',
        'invalid' => 'Invalid Token',
        'blacklisted' => 'Token blacklisted',
        'notexist' => 'Authorization Token Required'
    ],
    'password' => [
        'reset_send' => 'We have e-mailed your password reset link!',
        'reset_failed' => 'Email could not be sent to this email',
        'wrong_old' => 'Old password is wrong',
        'changed' => 'Password changed successfully',
        'empty' => 'Password Required',
        'invalid' => 'Invalid Password',
    ],
    'required_fields' => 'Please fill all mandatory fields',
    'success' => 'Success',
    'registration_success' => 'Registration completed',
    'invalid_credentials' => 'These credentials do not match our records',
    'login_success' => 'Successfully logged in',
    'no_data' => 'There is no data to display',
    'data_updated' => 'Updated successfully.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds',
    'user_exists' => 'This user already exists',
    'user_not_exist' => 'This user not exists',
    'email_exists' => 'The email has already been taken',
    'mobile_exists' => 'Mobile already exists',
    'invalid_request' => 'Invalid Request',
    'invalid_otp' => 'OTP is Invalid.',
    'otp_expired' => 'OTP expired.',
    'company_exist' => 'This company exist.',
    'customer_exist' => 'This Customer name exist.',
    'meeting' => [
        'not_done' => 'Meeting still not finished..',
        'business_card' => 'Business card u[dloaded successfully',
        'created' => 'Meeting created successfully.',
        'notes_created' => 'Meeting notes created successfully.',
        'feedback_exist' => 'Already submitted the feedback',
        'feedback_success' => 'Feedback submitted successfully.Thank you!',
        'already_shared_to' => 'This meeting is already shared to :name',
        'not_confirmed' => 'This meeting is shared already and not confirmed .',
        'shared' => 'Meeting shared successfully.',
    ],
    'notification' => [
        'title' => [
            'shared' => ':name shared a meeting',
            'accepted' => ':name accpted your meeting request',
            'rejected' => ':name rejected your meeting request',
            'cron' => 'Upcoming Meetings',
            'area' => 'Meeting with :name',
        ],
        'message' => [
            'cron' => 'You have :count upcoming meetings.',
        ]
    ],
    
    
];
