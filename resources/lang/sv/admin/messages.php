<?php

return [
    // Request errors
    'request_no_producer' => 'Kunde inte hitta producenten du efterfrågade',
    'request_no_product' => 'Kunde inte hitta produkten du efterfrågade',
    'request_no_variant' => 'Kunde inte hitta produktvarianten du efterfrågade',
    'request_no_order' => 'Kunde inte hitta beställningen du efterfrågade',
    'request_no_order_item' => 'Kunde inte hitta beställningsraden du efterfrågade',
    'request_no_event' => 'Kunde inte hitta evenemanget du efterfrågade',
    'request_no_node' => 'Kunde inte hitta noden du efterfrågade',

    // Event
    'event_created' => 'Ditt evenemang har skapats',
    'event_updated' => 'Ditt evenemang har uppdaterats',
    'event_deleted' => 'Ditt evenemang har tagits bort',

    // Node
    'node_created' => 'Din nod har skapats',
    'node_updated' => 'Din nod har uppdaterats',
    'node_deleted' => 'Din nod har tagits bort',

    // Producer
    'producer_created' => 'Ditt producentkonto har skapats',
    'producer_updated' => 'Ditt producentkonto har uppdaterats',
    'producer_deleted' => 'Ditt producentkonto har tagits bort',

    // Product
    'product_created' => 'Din produkt har skapats',
    'product_updated' => 'Din produkt har uppdaterats',
    'product_deleted' => 'Din produkt har tagits bort',
    'product_error_create_variant' => 'Kunde inte skapa produktvariant',
    'product_no_production' => 'Du har inte skapat någon produktion',
    'product_date_quantity_required' => 'Datum och kvantitet är obligatoriska fält för sällanprodukter',
    'product_delivery_updated' => 'Dina leveransdatum har uppdaterats',

    // Variant
    'variant_created' => 'Din variant har skapats',
    'variant_updated' => 'Din variant har uppdaterats',
    'variant_deleted' => 'Din variant har tagits bort',

    // Production
    'production_created' => 'Produktion har skapats',
    'production_updated' => 'Produktion har uppdaterats',
    'production_deleted' => 'Produktion har tagits bort',

    // Admin management
    'admin_removed' => 'Du är inte längre admin för :name',
    'invite_no_user' => 'Användaren finns inte',
    'invite_cancelled' => 'Inbjudan har tagits bort',
    'invite_accepted' => 'Du är nu admin för :name',
    'user_invited' => 'Användaren är redan inbjuden',
    'user_is_admin' => 'Användaren är redan admin',

    // User
    'user_account_email_sent' => 'Ett aktiveringsmail har skickats till din epost-adress.',
    'user_account_activated' => 'Ditt konto har aktiverats.',
    'user_account_created' => 'Ditt konto har skapats.',
    'user_account_updated' => 'Din information har uppdaterats',
    'user_account_deleted' => 'Ditt konto har tagits bort',
    'user_password_changed' => 'Ditt lösenord har ändrats',
    'user_membership_amount_not_numeric' => 'Avgiften är inte numeriskt. Måste vara en siffra',
    'user_membership_success' => 'Din medlemsavgift är nu betald.',
    'user_membership_errors' => 'Betalning av medlemsavgift kunde inte slutföras. :errors',

    // Password reset
    'password_reset_email_sent' => 'En återställningslänk har skickats till din epost-adress.',

    // Other
    'required_fields_missing' => 'Obligatoriska fält är tomma.',
    'required' => 'Obligatorisk',
    'session_expired' => 'Din session har gått ut och du måste logga in igen.',
    'order_deleted' => 'Din beställning har tagits bort.',

    // Auth
    'invalid_login_request' => 'Ogiltigt inloggningsanrop.',
    'invalid_access_token' => 'Ogiltig åtkomst.',
    'invalid_user_data' => 'Ogiltig användardata',
    'reset_link_expired' => 'Återställningslänken är ogiltig.',
    'invalid_login' => 'Felaktiga inloggningsuppgifter.',

    // Migration
    'user_migrate_not_valid' => 'Din epost kan inte migreras, vänligen skapa ett nytt konto.',
    'user_migrate_already_exists' => 'Ditt konto är redan migrerat.',
    'user_migrate_done' => 'Migrering färdig.',
];
