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
    exit('(-_*) This file cannot be accessed directly.');
}

// Constants
\define('TEDEM_HEADLIZE_ID', 'headlize');
\define('TEDEM_HEADLIZE_NAME', ucfirst(TEDEM_HEADLIZE_ID));
\define('TEDEM_HEADLIZE_AUTHOR', 'tedem');
\define('TEDEM_HEADLIZE_VERSION', '1.0.1');

// Hooks
$plugins->add_hook('datahandler_post_insert_thread', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_insert_thread_post', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_update_thread', 'headlize_convert_title');
$plugins->add_hook('datahandler_post_update', 'headlize_convert_title');

/**
 * Returns the plugin information.
 *
 * @return array The plugin information.
 */
function headlize_info(): array
{
    $description = <<<'HTML'
<div style="margin-top: 1em;">
    Automatically converts and saves thread titles in APA-style title case.
</div>
HTML;

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
        'codename' => TEDEM_HEADLIZE_AUTHOR.'_'.TEDEM_HEADLIZE_ID,
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
        $db->delete_query('datacache', "title='".TEDEM_HEADLIZE_AUTHOR."'");
    }
}

/**
 * Activates the plugin.
 */
function headlize_activate(): void
{
    //
}

/**
 * Deactivates the plugin.
 */
function headlize_deactivate(): void
{
    //
}

/**
 * Converts the title of a thread or post to title case.
 *
 * This function modifies the subject of a thread or post during insertion or update
 * by converting it to title case using the `headlize_title_case` function.
 *
 * @param  object  $datahandler  The data handler object that contains thread and post data.
 */
function headlize_convert_title(&$datahandler): void
{
    if (isset($datahandler->thread_insert_data['subject'])) {
        $datahandler->thread_insert_data['subject'] = headlize_title_case($datahandler->thread_insert_data['subject']);
    }

    if (isset($datahandler->post_insert_data['subject'])) {
        $datahandler->post_insert_data['subject'] = headlize_title_case($datahandler->post_insert_data['subject']);
    }

    if (isset($datahandler->thread_update_data['subject'])) {
        $datahandler->thread_update_data['subject'] = headlize_title_case($datahandler->thread_update_data['subject']);
    }

    if (isset($datahandler->post_update_data['subject'])) {
        $datahandler->post_update_data['subject'] = headlize_title_case($datahandler->post_update_data['subject']);
    }
}

/**
 * Converts a given title to title case, with specific rules for small words and special cases.
 *
 * @param  string  $title  The title to be converted.
 * @return string The title converted to title case.
 */
function headlize_title_case($title): string
{
    $small_words = [
        // English
        'a', 'an', 'and', 'as', 'at', 'but', 'by', 'for', 'if', 'in', 'nor',
        'of', 'on', 'or', 'so', 'the', 'to', 'up', 'yet',
        // Turkish
        'ama', 'bu', 'da', 'de', 'için', 'ile', 'ise', 'ki', 'mi', 'mu', 'mü',
        'o', 'şu', 've', 'veya', 'ya',
    ];

    $words = explode(' ', mb_strtolower($title));

    foreach ($words as $index => $word) {
        // Preserve and remove underscores from words
        if (preg_match('/^__(.*)__$/', $word, $matches)) {
            $words[$index] = $matches[1];

            continue;
        }

        // Preserve mybb as MyBB
        if (preg_match('/^mybb$/', $word)) {
            $words[$index] = 'MyBB';

            continue;
        }

        if ($index === 0 || $index === \count($words) - 1 || ! \in_array($word, $small_words)) {
            $words[$index] = ucfirst($word);
        }
    }

    // Preserve the last word's punctuation
    if (str_ends_with(end($words), '.')) {
        $words[\count($words) - 1] = mb_substr(end($words), 0, -1);
    }

    return implode(' ', $words);
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

    $BMC = '<a href="https://www.buymeacoffee.com/tedem"><b>Buy me a coffee</b></a>';
    $KOFI = '<a href="https://ko-fi.com/tedem"><b>KO-FI</b></a>';

    $close_link = 'index.php?module=config-plugins&'.TEDEM_HEADLIZE_AUTHOR.'-'.TEDEM_HEADLIZE_ID.'=deactivate-donation&my_post_key='.$mybb->post_code;
    $close_button = ' &mdash; <a href="'.$close_link.'"><b>Close Donation</b></a>';

    $message = '<b>Donation:</b> Support for new plugins, themes, etc. via '.$BMC.' or '.$KOFI.$close_button;

    return '<div style="margin-top: 1em;">'.$message.'</div>';
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

    return isset($donation[TEDEM_HEADLIZE_ID]['donation']) && $donation[TEDEM_HEADLIZE_ID]['donation'] === 1;
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

        if ($mybb->get_input(TEDEM_HEADLIZE_AUTHOR.'-'.TEDEM_HEADLIZE_ID) === 'deactivate-donation') {
            $plugins[TEDEM_HEADLIZE_ID]['donation'] = 0;

            $cache->update(TEDEM_HEADLIZE_AUTHOR, $plugins);

            flash_message('The donation message has been successfully closed.', 'success');
            admin_redirect('index.php?module=config-plugins');
        }
    }
}
