<?php

return [
    // Request errors
    'request_no_producer' => 'Could not find the producer account you requested',
    'request_no_product' => 'Could not find the product you requested',
    'request_no_variant' => 'Could not find the variant you requested',
    'request_no_order' => 'Could not find the order you requested',
    'request_no_order_item' => 'Could not find the order item you requested',
    'request_no_event' => 'Could not find the event you requested',
    'request_no_node' => 'Could not find the node account you requested',

    // Event
    'event_created' => 'Your event has been created',
    'event_updated' => 'Your event has been updated',
    'event_deleted' => 'Your event has been deleted',

    // Node
    'node_created' => 'Your node has been created',
    'node_updated' => 'Your node has been updated',
    'node_deleted' => 'Your node has been deleted',

    // Producer
    'producer_created' => 'Your producer account has been created',
    'producer_updated' => 'Your producer account has been updated',
    'producer_deleted' => 'Your producer account has been deleted',

    // Product
    'product_created' => 'Your product has been created',
    'product_updated' => 'Your product has been updated',
    'product_deleted' => 'Your product has been deleted',
    'product_error_create_variant' => 'Could not create product variant',
    'product_no_production' => 'You have not created any productions',
    'product_date_quantity_required' => 'Date and quantity are requried fields for occasional products',
    'product_delivery_updated' => 'Your product deliveries has been updated',

    // Variant
    'variant_created' => 'Your variant has been created',
    'variant_updated' => 'Your variant has been updated',
    'variant_deleted' => 'Your variant has been deleted',

    // Production
    'production_created' => 'Production has been created',
    'production_updated' => 'Production has been updated',
    'production_deleted' => 'Production has been deleted',

    // Admin management
    'admin_removed' => 'You are no longer an admin for :name',
    'invite_no_user' => 'Invited user does not exist.',
    'invite_cancelled' => 'Admin invite has been cancelled',
    'invide_accepted' => 'Your are now an admin of :name',
    'user_invited' => 'User is already invited',
    'user_is_admin' => 'User is already an admin',

    // User
    'user_account_email_sent' => 'An account activation link has been sent to your email.',
    'user_account_activated' => 'Your account has been activated.',
    'user_account_created' => 'Your account has been created.',
    'user_account_updated' => 'Your info has been changed',
    'user_account_deleted' => 'Your account has been deleted',
    'user_password_changed' => 'Your password has been changed',
    'user_membership_amount_not_numeric' => 'Membership fee is not numeric. Must be an integer.',
    'user_membership_success' => 'Din medlemsavgift är nu betald.',
    'user_membership_error' => 'Membership payment could not be completed (:errors)',

    // Password reset
    'password_reset_email_sent' => 'We have e-mailed your password reset link.',

    // Other
    'required_fields_missing' => 'Required fields are empty.',
    'required' => 'Required',
];
