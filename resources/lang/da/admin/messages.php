<?php

return [
    // Request errors
    'request_no_producer' => 'Kunne ikke finde producenten.',
    'request_no_product' => 'Kunne ikke finde produktet.',
    'request_no_variant' => 'Kunne ikke finde varianten.',
    'request_no_order' => 'Kunne ikke finde ordren.',
    'request_no_order_item' => 'Kunne ikke finde varen på ordren.',
    'request_no_event' => 'Kunne ikke finde din begivenhed.',
    'request_no_node' => 'Kunne ikke finde node kontoen.',

    // Event
    'event_created' => 'Din begivenhed er oprettet.',
    'event_updated' => 'Din begivenhed er ændret.',
    'event_deleted' => 'Din begivenhed er slettet.',

    // Node
    'node_created' => 'Din node er oprettet.',
    'node_updated' => 'Din node er opdateret.',
    'node_deleted' => 'Din node er slettet.',

    // Producer
    'producer_created' => 'Din producentkonto er oprettet.',
    'producer_updated' => 'Din producentkonto er opdateret.',
    'producer_deleted' => 'Din producentkonto er slettet.',

    // Product
    'product_created' => 'Dit produkt er oprettet.',
    'product_updated' => 'Dit produkt er opdateret.',
    'product_deleted' => 'Dit produkt er slettet.',
    'product_error_create_variant' => 'Kunne ikke oprette produktvariant.',
    'product_no_production' => 'Du har ikke oprettet nogen produktioner.',
    'product_date_quantity_required' => 'Dato og antal skal udfyldes.',
    'product_delivery_updated' => 'Levering af produkter er opdateret.',

    // Variant
    'variant_created' => 'Din variant er oprettet.',
    'variant_updated' => 'Din variant er opdateret.',
    'variant_deleted' => 'Din variant er slettet.',

    // Production
    'production_created' => 'Produktionen er oprettet.',
    'production_updated' => 'Produktionen er opdateret.',
    'production_deleted' => 'Produktionen er slettet.',

    // Admin management
    'admin_removed' => 'Du er ikke længere admin for :name.',
    'invite_no_user' => 'Inviterede bruger findes ikke.',
    'invite_cancelled' => 'Admin invitation er ikke længere gyldig.',
    'invite_accepted' => 'Din er nu admin for :name.',
    'invite_sent' => 'Invitation er blevet sendt.',
    'user_invited' => 'Bruger er allerede inviteret.',
    'user_is_admin' => 'Bruger er allerede admin.',

    // User
    'user_account_email_sent' => 'Der er sendt et link til at aktivere din konto på email.',
    'user_account_activated' => 'Din konto er aktiveret.',
    'user_account_activation_failed' => 'Din konto kunne ikke aktiveres. Kontakt venligst support for hjælp.',
    'user_account_created' => 'Din konto er oprettet.',
    'user_account_updated' => 'Din oplysninger er ændret.',
    'user_account_deleted' => 'Din konto er slettet.',
    'user_password_changed' => 'Din adgangskode er ændret.',
    'user_password_not_changed' => 'Din adgangskode kunne ikke ændres.',
    'user_membership_amount_not_numeric' => 'Beløbet for medlemsskab er ikke er tal.',
    'user_membership_success' => 'Dit medlemsskab er nu betalt.',
    'user_membership_error' => 'Medlemskabsbetaling kunne ikke gennemføres. :errors',

    // Password reset
    'password_reset_email_sent' => 'Vi har sendt et link til at nulstille din adgangskode.',

    // Other
    'required_fields_missing' => 'Påkrævede felter er tomme.',
    'required' => 'Påkrævet.',
    'session_expired' => 'Din session er udløbet, log in igen.',
    'order_deleted' => 'Order er slettet.',

    // Auth
    'invalid_login_request' => 'Ugyldig login forespørgsel.',
    'invalid_access_token' => 'Ugyldig access token.',
    'invalid_user_data' => 'Ugyldig brugerdata.',
    'reset_link_expired' => 'Link til nulstilling af passsword er løbet ud.',
    'invalid_login' => 'Forkert login',

    // Migration
    'user_migrate_not_valid' => 'Email kan ikke migreres. Opret venligst en ny konto.',
    'user_migrate_already_exists' => 'Email er allerde migreret.',
    'user_migrate_done' => 'Migræring færdig',
];
