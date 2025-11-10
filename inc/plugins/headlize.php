<?php

declare(strict_types=1);

/**
 * Headlize
 *
 * Automatically converts and saves thread titles in APA-style title case.
 *
 * @author Medet "tedem" Erdal <hello@tedem.dev>
 *
 * @version 1.0.1
 */

// Disallow direct access to this file for security reasons
if (! \defined('IN_MYBB')) {
    exit(headlize_translate('direct_access_error'));
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    exit(\sprintf(headlize_translate('php_version_error'), PHP_VERSION));
}

// Constants
\define('TEDEM_HEADLIZE_ID', 'headlize');
\define('TEDEM_HEADLIZE_NAME', ucfirst(TEDEM_HEADLIZE_ID));
\define('TEDEM_HEADLIZE_AUTHOR', 'tedem');
\define('TEDEM_HEADLIZE_VERSION', '1.0.1');

// Hooks
$plugins->add_hook('datahandler_post_insert_thread', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_insert_thread_post', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_insert_post', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_update_thread', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_update', 'headlize_convert_title');

/**
 * Returns the plugin information.
 *
 * @return array The plugin information.
 */
function headlize_info(): array
{
    $description = headlize_translate('plugin_description');
    $description = '<div style="margin-top: 1em;">' . $description . '</div>';

    if (headlize_donation_status()) {
        $description .= headlize_donation();
    }

    return [
        'name' => TEDEM_HEADLIZE_NAME,
        'description' => $description,
        'website' => 'https://mybbcode.com/',
        'author' => TEDEM_HEADLIZE_AUTHOR,
        'authorsite' => 'https://tedem.dev/',
        'version' => TEDEM_HEADLIZE_VERSION,
        'codename' => TEDEM_HEADLIZE_AUTHOR . '_' . TEDEM_HEADLIZE_ID,
        'compatibility' => '18*',
    ];
}

/**
 * Installs the plugin.
 */
function headlize_install(): void
{
    global $cache;

    $plugins = $cache->read(TEDEM_HEADLIZE_AUTHOR);

    $plugins[TEDEM_HEADLIZE_ID] = [
        'name' => TEDEM_HEADLIZE_NAME,
        'author' => TEDEM_HEADLIZE_AUTHOR,
        'version' => TEDEM_HEADLIZE_VERSION,
        'donation' => 1,
    ];

    $cache->update(TEDEM_HEADLIZE_AUTHOR, $plugins);
}

/**
 * Checks if the plugin is installed.
 */
function headlize_is_installed(): bool
{
    global $cache;

    $plugins = $cache->read(TEDEM_HEADLIZE_AUTHOR);

    return isset($plugins[TEDEM_HEADLIZE_ID]);
}

/**
 * Uninstalls the plugin.
 */
function headlize_uninstall(): void
{
    global $db, $cache;

    $plugins = $cache->read(TEDEM_HEADLIZE_AUTHOR);

    unset($plugins[TEDEM_HEADLIZE_ID]);

    $cache->update(TEDEM_HEADLIZE_AUTHOR, $plugins);

    if (\count($plugins) === 0) {
        $db->delete_query('datacache', "title='" . TEDEM_HEADLIZE_AUTHOR . "'");
    }
}

/**
 * Activates the plugin.
 */
function headlize_activate(): void
{

}

/**
 * Deactivates the plugin.
 */
function headlize_deactivate(): void
{

}

/**
 * Converts the title of a thread or post to title case.
 *
 * This function modifies the subject of a thread or post during insertion or update
 * by converting it to title case using the `headlize_title_case` function.
 *
 * @param object $datahandler The data handler object that contains thread and post data.
 */
function headlize_convert_title(&$datahandler): void
{
    if (isset($datahandler->thread_insert_data['subject'])) {
        $datahandler->thread_insert_data['subject'] = headlize_title_case($datahandler->thread_insert_data['subject']);
    }

    if (isset($datahandler->post_insert_data['subject'])) {
        $datahandler->post_insert_data['subject'] = headlize_title_case($datahandler->post_insert_data['subject']);

        // Add "RE:" prefix if the post is a reply
        $datahandler->post_insert_data['subject'] = headlize_add_reply_prefix($datahandler->post_insert_data['subject']);
    }

    if (isset($datahandler->thread_update_data['subject'])) {
        $datahandler->thread_update_data['subject'] = headlize_title_case($datahandler->thread_update_data['subject']);
    }

    if (isset($datahandler->post_update_data['subject'])) {
        $datahandler->post_update_data['subject'] = headlize_title_case($datahandler->post_update_data['subject']);

        // Add "RE:" prefix if the post is a reply
        $datahandler->post_update_data['subject'] = headlize_add_reply_prefix($datahandler->post_update_data['subject']);
    }
}

/**
 * Converts a given title to title case, with specific rules for small words and special cases.
 *
 * @param string $title The title to be converted.
 *
 * @return string The title converted to title case.
 */
function headlize_title_case($title): string
{
    global $db;

    $smallWords = [
        // English (APA 7th Edition)
        // Source: American Psychological Association (2020). Publication Manual of the American Psychological Association (7th ed.), Section 6.17: Title Case.
        // Additional References: Oxford English Dictionary, Merriam-Webster Dictionary
        'a', 'an', 'and', 'as', 'at', 'but', 'by', 'for', 'if', 'in', 'nor',
        'of', 'on', 'or', 'per', 'so', 'the', 'to', 'up', 'via', 'yet',

        // Turkish (TDK)
        // Source: Turkish Language Association (TDK) — Writing Guide: Conjunctions and Postpositions (https://www.tdk.gov.tr/)
        'ama', 'ancak', 'da', 'de', 'diye', 'gibi', 'göre', 'için', 'ile',
        'ise', 'kadar', 'ki', 'mi', 'mı', 'mu', 'mü', 've', 'veya', 'ya', 'ya da',
        'üzere', 'sonra', 'önce',
    ];

    $titleCaseExceptions = [
        // Common acronyms and abbreviations
        'API', 'ASCII', 'CPU', 'CSS', 'DNS', 'FTP', 'GPU', 'GUI', 'HTTP', 'HTTPS',
        'ID', 'IDE', 'IP', 'LAN', 'OS', 'RAM', 'ROM', 'SDK', 'SQL', 'SSH',
        'TCP', 'UDP', 'UI', 'URL', 'USB', 'UX', 'VPN', 'WiFi', 'XML',

        // Programming languages and technologies
        'C', 'C++', 'C#', 'CSS3', 'HTML', 'HTML5', 'JavaScript', 'JSON', 'PHP',

        // Frameworks and libraries
        'AJAX', 'ASP.NET', 'Django', 'jQuery', 'Node.js', 'React', 'Vue.js',

        // Open-source projects and platforms
        'GitHub', 'MyBB', 'MySQL', 'OpenSSL', 'PostgreSQL', 'WordPress',
    ];

    $words = explode(' ', mb_strtolower($title));

    foreach ($words as $index => $word) {
        // Preserve and remove underscores from words
        if (preg_match('/^__(.*)__$/', $word, $matches)) {
            $words[$index] = $matches[1];

            continue;
        }

        // Preserve exceptions
        foreach ($titleCaseExceptions as $exception) {
            if (mb_strtolower($word) === mb_strtolower($exception)) {
                // Preserve the original form as defined in the exceptions list
                $words[$index] = $exception;

                continue 2; // skip to next word
            }
        }

        // Capitalize all words except small words
        if ($index === 0 || $index === \count($words) - 1 || ! \in_array($word, $smallWords)) {
            $words[$index] = ucfirst($word);
        }
    }

    // Remove trailing dots (., .., ...) from the last word if they exist
    $lastWordKey = \count($words) - 1;
    $words[$lastWordKey] = rtrim($words[$lastWordKey], '.');

    return $db->escape_string(implode(' ', $words));
}

/**
 * Adds a reply prefix to the subject if it doesn't already exist.
 *
 * @param string $subject The original subject.
 * @param string $prefix  The prefix to add (default is 'RE:').
 *
 * @return string The subject with the reply prefix added if it was not present.
 */
function headlize_add_reply_prefix(string $subject, string $prefix = 'RE:'): string
{
    if (mb_strpos($subject, $prefix) !== 0) {
        return $prefix . ' ' . $subject;
    }

    return $subject;
}

/**
 * Generates a donation message with links to support the developer.
 *
 * This function creates a donation message that includes links to "Buy me a coffee" and "KO-FI"
 * for supporting the developer. It also includes a link to close the donation message.
 *
 * @global object $mybb The MyBB core object.
 *
 * @return string The HTML string containing the donation message.
 */
function headlize_donation(): string
{
    global $mybb;

    headlize_donation_edit();

    $closeLink = 'index.php?module=config-plugins&' . TEDEM_HEADLIZE_AUTHOR . '-' . TEDEM_HEADLIZE_ID . '=deactivate-donation&my_post_key=' . $mybb->post_code;
    $closeButton = '&mdash; <a href="' . $closeLink . '"><b>Close Donation</b></a>';

    $message = \sprintf(
        headlize_translate('donation_message'),
        '<a href="https://www.buymeacoffee.com/tedem"><b>Buy me a coffee</b></a>',
        '<a href="https://ko-fi.com/tedem"><b>KO-FI</b></a>',
        $closeButton
    );

    return '<div style="margin-top: 1em;">' . $message . '</div>';
}

/**
 * Checks the donation status for the current user.
 *
 * This function reads the donation status from the cache and determines
 * if the user has made a donation.
 *
 * @global array $cache The global cache array.
 *
 * @return bool True if the user has made a donation, false otherwise.
 */
function headlize_donation_status(): bool
{
    global $cache;

    $donation = $cache->read(TEDEM_HEADLIZE_AUTHOR);

    return ($donation[TEDEM_HEADLIZE_ID]['donation'] ?? 0) === 1;
}

/**
 * Handles the donation edit action.
 *
 * This function checks if the provided post key matches the expected post code.
 * If the post key is valid and the donation action is set to 'deactivate-donation',
 * it updates the plugin's donation status to inactive and updates the cache.
 * A success message is then flashed and the user is redirected to the plugins configuration page.
 *
 * @global array $mybb The MyBB core object containing request data.
 * @global object $cache The MyBB cache object used to read and update cache data.
 */
function headlize_donation_edit(): void
{
    global $mybb;

    if ($mybb->get_input('my_post_key') === $mybb->post_code) {
        global $cache;

        $plugins = $cache->read(TEDEM_HEADLIZE_AUTHOR);

        if ($mybb->get_input(TEDEM_HEADLIZE_AUTHOR . '-' . TEDEM_HEADLIZE_ID) === 'deactivate-donation') {
            $plugins[TEDEM_HEADLIZE_ID]['donation'] = 0;

            $cache->update(TEDEM_HEADLIZE_AUTHOR, $plugins);

            flash_message(headlize_translate('donation_flash_success'), 'success');
            admin_redirect('index.php?module=config-plugins');
        }
    }
}

/**
 * Retrieves internationalized text for the plugin.
 *
 * This function returns the corresponding text for a given key.
 * If the key does not exist, it returns the key itself.
 *
 * @param string $key The key for which to retrieve the text.
 *
 * @return string The internationalized text corresponding to the key.
 */
function headlize_translate(string $key): string
{
    // Internationalized texts
    static $texts = [
        'direct_access_error' => '(-_*) This file cannot be accessed directly.',
        'php_version_error' => '(T_T) Headlize requires PHP version 7.4.0 or higher. You are running PHP version %s.',
        'plugin_description' => 'Automatically converts and saves thread titles in APA-style title case.',
        'donation_message' => 'If you find this plugin useful, consider supporting its development via %s or %s %s',
        'donation_flash_success' => 'The donation message has been successfully closed.',
    ];

    return $texts[$key] ?? $key;
}
