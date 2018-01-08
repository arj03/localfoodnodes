<?php

return [
    // Request errors
    'request_no_producer' => 'De aangevraagde producentaccount kan niet worden gevonden.',
    'request_no_product' => 'Het aangevraagde product kon niet worden gevonden.',
    'request_no_variant' => 'De aangevraagde variant kon niet worden gevonden.',
    'request_no_order' => 'De aangevraagde bestelling kon niet worden gevonden.',
    'request_no_order_item' => 'Het aangevraagde item kon niet worden gevonden.',
    'request_no_event' => 'Het aangevraagde evenement kon niet worden gevonden.',
    'request_no_node' => 'De aangevraagde knooppuntaccount kan niet worden gevonden.',

    // Event
    'event_created' => 'Je evenement is gecreërd.',
    'event_updated' => 'Je evenement is bijgewerkt.',
    'event_deleted' => 'Je evenement is verwijderd.',

    // Node
    'node_created' => 'Je knooppunt is gecreërd.',
    'node_updated' => 'Je knooppunt is bijgewerkt.',
    'node_deleted' => 'Je knooppunt is verwijderd.',

    // Producer
    'producer_created' => 'Je producentaccount is gecreërd.',
    'producer_updated' => 'Je producentaccount is bijgewerkt.',
    'producer_deleted' => 'Je producentaccount is verwijderd.',

    // Product
    'product_created' => 'Je product is gecreërd.',
    'product_updated' => 'Je product is bijgewerkt.',
    'product_deleted' => 'Je product is verwijderd.',
    'product_error_create_variant' => 'Kan geen nieuwe productvariant creëren.',
    'product_no_production' => 'Je hebt nog geen producties gecreërd.',
    'product_date_quantity_required' => 'De datum en hoeveelheid zijn verplichte velden voor variabele producten.',
    'product_delivery_updated' => 'Je productleveringen zijn bijgewerkt.',

    // Variant
    'variant_created' => 'Je variant is gecreëerd.',
    'variant_updated' => 'Je variant is bijgewerkt.',
    'variant_deleted' => 'Je variant is verwijderd.',

    // Production
    'production_created' => 'Productie is gecreërd.',
    'production_updated' => 'Productie is bijgewerkt.',
    'production_deleted' => 'Productie is verwijderd.',

    // Admin management
    'admin_removed' => 'Je bent geen beheerder voor :name meer.',
    'invite_no_user' => 'Uitgenodigde gebruiker bestaat niet.',
    'invite_cancelled' => 'Uitnodiging van beheerder is geannuleerd.',
    'invite_accepted' => 'Je bent nu beheerder van :name.',
    'invite_sent' => 'Uitnodiging is verstuurd.',
    'user_invited' => 'Gebruiker is al uitgenodigd.',
    'user_is_admin' => 'Gebruiker is al beheerder.',

    // User
    'user_account_email_sent' => 'Je account activeringslink is verstuurd naar je e-mailadres.',
    'user_account_activated' => 'Je account is geactiveerd.',
    'user_account_activation_failed' => 'Je account kon niet worden geactiveerd. Neem contact met ons op voor hulp.',
    'user_account_created' => 'Je account is aangemaakt.',
    'user_account_updated' => 'Je informatie is bijgewerkt.',
    'user_account_deleted' => 'Je account is verwijderd.',
    'user_password_changed' => 'Je wachtwoord is bijgewerkt.',
    'user_password_not_changed' => 'Je paswoord kon niet worden veranderd.',
    'user_membership_amount_not_numeric' => 'Ledencontributie is numeriek. Moet een heel getal zijn.',
    'user_membership_success' => 'Jouw ledencontributie is nu betaald.',
    'user_membership_error' => 'Betaling ledencontributie kon niet worden voltooid. :errors',

    // Password reset
    'password_reset_email_sent' => 'Wij hebben je een link verstuurd per em-mail om je wachtwoord opnieuw in te stellen.',

    // Other
    'required_fields_missing' => 'Verplichte velden zijn leeg.',
    'required' => 'Verplicht.',
    'session_expired' => 'Je sessie is verlopen, log opnieuw in.',
    'order_deleted' => 'Bestelling is verwijderd.',

    // Auth
    'invalid_login_request' => 'Ongeldig inlogverzoek.',
    'invalid_access_token' => 'Ongeldige toegangstoken.',
    'invalid_user_data' => 'Ongeldige gebruikersdata.',
    'reset_link_expired' => 'De link om je wachtwoord opnieuw in te stellen is verlopen.',
    'invalid_login' => 'Incorrecte inlog referenties.',

    // Migration
    'user_migrate_not_valid' => 'E-mailadres was niet beschikbaar voor overplaatsing, maak alstublieft een nieuwe account aan.',
    'user_migrate_already_exists' => 'E-mailadres is al overgeplaatst.',
    'user_migrate_done' => 'Overplaatsing voltooid.',
];
