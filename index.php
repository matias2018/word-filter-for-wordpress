<?php
/*
    Plugin Name: NDD Word Filter
    Plugin URI: https://github.com/matias2018/word-filter-for-wordpress.git
    Description: Plugin to search and replace words in the content of the site
    Version: 1.0
    Author: Pedro Matias
    Author URI: https://pedromatias.dev
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NDD_WordFilterPlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'ourMenu'));
        add_action('admin_init', array($this, 'ourSettings'));
        if(get_option('plugin_word_filter')) add_filter('the_content', array($this, 'filterLogic'));
    }

    function ourSettings() {
        // add_settings_section(1, 2, 3, 4);
        add_settings_section('replacement-text-section', NULL, NULL, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');
        // add_settings_field(1, 2, 3, 4, 5);
        add_settings_field('replacement-text', 'Filtered Text', array($this, 'replacementFieldHTML'), 'word-filter-options', 'replacement-text-section');
    }

    function replacementFieldHTML() { ?>
        <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')); ?>">
        <p class="description">Leave blank to simple remove the filtered words.</p>
    <?php }

    function filterLogic($content) {
        $searchedWords = explode(',', get_option('plugin_word_filter'));
        $searchedWordsTrimmed = array_map('trim', $searchedWords);
        // str_ireplace(1, 2, 3);
        return str_ireplace($searchedWordsTrimmed, esc_html(get_option('replacementText')), $content);
    }

    function ourMenu() {
        // add_menu_page(1, 2, 3, 4, 5, 6, 7);
        // add_submenu_page(1, 2, 3, 4, 5, 6);
        $mainPageHook = add_menu_page('NDD Word Filter', 'NDD Word Filter', 'manage_options', 'nddwordfilter', array($this, 'wordFilterPage'), 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYW1hZGFfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiCgkgdmlld0JveD0iMCAwIDg3LjQ2IDEzMS4yOCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgODcuNDYgMTMxLjI4OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTQzLjg2LDEzMS4yOGMtMC4wNCwwLTAuMDksMC0wLjEzLDBjLTAuMDQtMC4zNC0wLjI2LTAuNTctMC40OC0wLjhjLTEuMzMtMS40Mi0yLjc4LTIuNy00LjM5LTMuOAoJCWMtMS4zNy0wLjk0LTIuNjktMS45NS0zLjktMy4wOWMtNS4yMy00LjkxLTguNzItMTAuODMtMTAuMjYtMTcuODZjLTAuMTMtMC42MS0wLjM1LTAuNzEtMC45LTAuNzEKCQljLTcuNzEsMC4wMi0xNS40MywwLjAxLTIzLjE0LDAuMDJjLTAuNTMsMC0wLjY3LTAuMTQtMC42Ny0wLjY3YzAuMDItMy45MywwLjAxLTcuODYsMC4wMS0xMS43OWMwLTEzLjQ2LDAuMDEtMjYuOTIsMC4wMS00MC4zOAoJCWMwLTEzLjQ2LDAtMjYuOTItMC4wMS00MC4zOGMwLTAuNzEsMC0xLjQyLDAtMi4yYzAuMzEsMC4xNCwwLjUzLDAuMjMsMC43MywwLjMzYzYuNjgsMy40MiwxMi4xLDguMjksMTYuMjksMTQuNDkKCQljNC4wNiw2LjAxLDYuNTQsMTIuNTksNy4xLDE5Ljg4YzAuMTYsMi4xNCwwLjUyLDQuMjcsMC43OSw2LjRjMC4yOCwyLjIyLDAuNTcsNC40MywwLjg1LDYuNjVjMC4yOCwyLjIyLDAuNTcsNC40MywwLjg1LDYuNjUKCQljMC4yLDEuNTYsMC4zOCwzLjEzLDAuNTgsNC42OWMwLjA0LDAuMjksMC4wMiwwLjQ1LTAuMzUsMC4zOGMtMi4wMy0wLjM5LTMuODMsMC4zMS01LjUyLDEuMjljLTMuNTEsMi4wNC00LjIxLDYuMjgtMS40OSw5LjE4CgkJYzEuOTcsMi4xLDQuNTEsMi43OSw3LjMzLDIuNDRjMC40My0wLjA1LDAuODMtMC4xMiwxLjE0LTAuNTVjMS41LTIuMDcsMi44MS00LjI2LDQtNi41MmMxLjc1LTMuMzMsMy4xNy02LjgsNC4zLTEwLjQKCQljMS41Mi00Ljg1LDIuNDYtOS44MSwyLjczLTE0Ljg5YzAuMjgtNS4yNywwLjAxLTEwLjU0LTAuMTQtMTUuODFjLTAuMTEtMy42Mi0wLjM1LTcuMjQtMC40Ny0xMC44N2MtMC4xMS0zLjEzLTAuMjctNi4yNy0wLjI5LTkuNDEKCQljLTAuMDItMi43OCwwLjQ5LTUuNDQsMS41OC04YzAuODktMi4wOCwyLjE5LTMuODgsMy42OS01LjU1YzAuMDQsMCwwLjA5LDAsMC4xMywwYzAuMDksMC4xMiwwLjE3LDAuMjQsMC4yNywwLjM0CgkJQzQ3LjIsMy42Myw0OC44OCw3LjU0LDQ5LjA5LDEyYzAuMjIsNC44Mi0wLjIzLDkuNjQtMC4zOSwxNC40NmMtMC4xMywzLjgyLTAuMzYsNy42My0wLjQzLDExLjQ1Yy0wLjA4LDQuMjYtMC4yNCw4LjUzLDAuMDcsMTIuOAoJCWMwLjE2LDIuMjEsMC40Myw0LjQxLDAuODQsNi41OGMwLjksNC43OSwyLjMzLDkuNDMsNC4zLDEzLjg5YzEuNTksMy42MSwzLjQ5LDcuMDUsNS44LDEwLjI1YzAuMjIsMC4zLDAuNDcsMC40NSwwLjgzLDAuNTEKCQljMi41MiwwLjQzLDQuODItMC4xLDYuODctMS42NWMyLjU1LTEuOTIsMy4yNy01LjU0LDEuNDEtOC4wMWMtMS44MS0yLjQtNC4zOC0zLjQ1LTcuMzktMy4yNGMtMC41OSwwLjA0LTAuNjUtMC4xMy0wLjU5LTAuNTgKCQljMC4xMS0wLjgyLDAuMjItMS42NCwwLjMzLTIuNDdjMC4yOC0yLjEzLDAuNTUtNC4yNiwwLjgyLTYuMzljMC4zMi0yLjUzLDAuNjItNS4wNywwLjk0LTcuNmMwLjMyLTIuNTgsMC42Ni01LjE1LDAuOTktNy43MwoJCWMwLjIyLTEuNzEsMC4zLTMuNDUsMC42OC01LjEyYzEuMTUtNS4wMywzLjEzLTkuNzMsNS45Ni0xNC4wNmMyLjI3LTMuNDgsNC45OC02LjU4LDguMTMtOS4zMWMyLjYtMi4yNSw1LjM5LTQuMjIsOC40Ny01Ljc3CgkJYzAuNzItMC4zNiwwLjczLTAuMzUsMC43MywwLjQzYzAsMTIuMzcsMC4wMSwyNC43NCwwLjAxLDM3LjExYzAsMTguOTEsMCwzNy44MSwwLjAxLDU2LjcyYzAsMC42Mi0wLjE1LDAuNzctMC43NywwLjc3CgkJYy03LjYxLTAuMDItMTUuMjEtMC4wMS0yMi44Mi0wLjAxYy0wLjE3LDAtMC4zNCwwLjAxLTAuNTEsMGMtMC4yNy0wLjAxLTAuMzYsMC4xMy0wLjQxLDAuMzhjLTAuNzIsMy44LTIuMTgsNy4zMS00LjE2LDEwLjYyCgkJYy0xLjg2LDMuMDktNC4xNiw1LjgtNi44Nyw4LjE5Yy0xLjg0LDEuNjItMy45OSwyLjg0LTUuNzgsNC41MkM0NS4zMSwxMjkuNTIsNDQuMzQsMTMwLjE4LDQzLjg2LDEzMS4yOHogTTcuNDQsMjMuOAoJCWMwLDAuMjMsMCwwLjQ2LDAsMC42OGMwLDE1LjY0LDAsMzEuMjcsMCw0Ni45MWMwLDAuMTEsMC4wMSwwLjIyLDAsMC4zMmMtMC4wNSwwLjM5LDAuMSwwLjUyLDAuNDksMC41MWMwLjk4LTAuMDIsMS45Ny0wLjAzLDIuOTUsMAoJCWMwLjQyLDAuMDEsMC41OS0wLjE1LDAuNy0wLjU0YzAuMzctMS4zMywxLjAyLTIuNTIsMS44OS0zLjU5YzEuNDYtMS44LDMuMjctMy4xNSw1LjM3LTQuMTNjMC4yMy0wLjExLDAuNDUtMC4xNiwwLjM5LTAuNTMKCQljLTAuMjktMS45OC0wLjU2LTMuOTYtMC44My01Ljk0Yy0wLjQtMi45Ny0wLjgxLTUuOTQtMS4yLTguOTFjLTAuMzctMi43Ni0wLjY4LTUuNTMtMS4xLTguMjhjLTAuMy0yLjAyLTEuMDQtMy45My0xLjg1LTUuOAoJCUMxMi41NywzMC41OSwxMC4yNywyNy4wNSw3LjQ0LDIzLjh6IE04MC4xNSwyMy44MmMtMC4wNC0wLjAyLTAuMDktMC4wNC0wLjEzLTAuMDVjLTAuNiwwLjc3LTEuMjEsMS41My0xLjc5LDIuMzIKCQljLTIuNiwzLjU2LTQuNzEsNy4zNy02LjA4LDExLjU4Yy0wLjY5LDIuMS0wLjg2LDQuMjgtMS4xNSw2LjQ1Yy0wLjQ2LDMuMzMtMC45Miw2LjY2LTEuMzYsMTBjLTAuNDEsMy4wNi0wLjc4LDYuMTItMS4yMSw5LjE4CgkJYy0wLjA2LDAuNDUsMC4wOCwwLjU1LDAuNDIsMC43MmMzLjM5LDEuNjksNi4wOSw0LjA0LDcuMjIsNy44M2MwLjA5LDAuMjksMC4yNCwwLjQsMC41NSwwLjRjMC45Mi0wLjAyLDEuODQtMC4wNSwyLjc1LDAuMDEKCQljMC42NSwwLjA0LDAuOC0wLjE2LDAuOC0wLjhjLTAuMDItMTUuNjYtMC4wMi0zMS4zMi0wLjAyLTQ2Ljk4QzgwLjE1LDI0LjI0LDgwLjE1LDI0LjAzLDgwLjE1LDIzLjgyeiBNNy40NCw4OC43MgoJCWMwLDMuMSwwLjAxLDYuMTktMC4wMSw5LjI5YzAsMC40MSwwLjA1LDAuNiwwLjU0LDAuNmM1LjA4LTAuMDIsMTAuMTYtMC4wMSwxNS4yNCwwYzAuMzcsMCwwLjUyLTAuMDcsMC41My0wLjQ5CgkJYzAuMDUtMy4wMywwLjQ2LTYuMDIsMS4zOC04LjkyYzAuMDktMC4yOSwwLjA3LTAuNDYtMC4zMS0wLjQ4Yy0xLjcxLTAuMTItMy4zNS0wLjUzLTQuOTQtMS4xNWMtNC4wMi0xLjU1LTcuMS00LjAzLTguMzMtOC4zNgoJCWMtMC4wOS0wLjMxLTAuMjYtMC4zOC0wLjU2LTAuMzdjLTAuOTgsMC4wMi0xLjk3LDAuMDMtMi45NS0wLjAxYy0wLjQ5LTAuMDItMC42LDAuMTMtMC42LDAuNkM3LjQ1LDgyLjUzLDcuNDQsODUuNjIsNy40NCw4OC43MnoKCQkgTTgwLjE1LDg4LjcyYzAtMy4xLTAuMDEtNi4xOSwwLjAxLTkuMjljMC0wLjQ4LTAuMTItMC42Mi0wLjYtMC42Yy0wLjk0LDAuMDQtMS44OCwwLjA0LTIuODIsMGMtMC40NS0wLjAyLTAuNiwwLjE1LTAuNzIsMC41NwoJCWMtMC41LDEuNzktMS40MSwzLjM3LTIuNzMsNC42OGMtMi44NiwyLjgzLTYuNDEsNC4xNS0xMC4zMiw0LjYzYy0wLjM1LDAuMDQtMC42MiwwLjAzLTAuNDYsMC41NGMwLjkxLDIuODYsMS4yOSw1LjgsMS4zNiw4Ljc5CgkJYzAuMDEsMC40NCwwLjEzLDAuNTcsMC41OCwwLjU3YzUuMDQtMC4wMiwxMC4wOC0wLjAyLDE1LjEyLDBjMC40OCwwLDAuNjEtMC4xMiwwLjYtMC42QzgwLjEzLDk0LjkxLDgwLjE1LDkxLjgxLDgwLjE1LDg4LjcyegoJCSBNNDMuODIsNjYuMTdjLTAuMDksMC4yMy0wLjEzLDAuMzItMC4xNiwwLjQyYy0yLDYuMDMtNC40MywxMS44NS04LjA2LDE3LjExYy0wLjE5LDAuMjctMC4wOSwwLjM2LDAuMTIsMC40OAoJCWMyLjU1LDEuNTMsNS4xLDMuMDYsNy42Myw0LjYyYzAuMzcsMC4yMywwLjYzLDAuMTksMC45Ny0wLjAyYzIuNS0xLjUzLDUuMDEtMy4wNiw3LjUyLTQuNTZjMC4zLTAuMTgsMC4zNy0wLjI4LDAuMTQtMC41OQoJCWMtMi4zNS0zLjMxLTQuMTMtNi45My01LjY4LTEwLjY3QzQ1LjM5LDcwLjc2LDQ0LjU3LDY4LjUzLDQzLjgyLDY2LjE3eiBNNDcuNTcsMTE4LjUzYzEuMjgtMS4wNCwyLjI3LTIuMTIsMy4xNi0zLjI5CgkJYzIuMTUtMi43OSwzLjUyLTUuOTMsNC4yNi05LjM2YzAuMTgtMC44NSwwLjE3LTAuODUtMC43Mi0wLjg1Yy0yLjAzLDAtNC4wNiwwLjAyLTYuMDgtMC4wMWMtMC41LTAuMDEtMC42MywwLjEzLTAuNjMsMC42MwoJCWMwLjAyLDMuOTMsMC4wMSw3Ljg2LDAuMDEsMTEuNzhDNDcuNTcsMTE3Ljc0LDQ3LjU3LDExOC4wNiw0Ny41NywxMTguNTN6IE0zOS44OCwxMTguNDFjMC4yLTAuMDgsMC4xMy0wLjIyLDAuMTMtMC4zNAoJCWMwLTQuMTktMC4wMS04LjM3LDAuMDEtMTIuNTZjMC0wLjQ5LTAuMjQtMC40OS0wLjU5LTAuNDljLTIuMDksMC4wMS00LjE5LDAuMDMtNi4yOC0wLjAxYy0wLjY2LTAuMDEtMC42OCwwLjI0LTAuNTgsMC43NAoJCWMwLjM5LDEuODgsMC45NywzLjY5LDEuOCw1LjQyQzM1LjcyLDExMy45NiwzNy41MSwxMTYuNDEsMzkuODgsMTE4LjQxeiBNNTEuOTQsOTguNWMwLDAsMCwwLjAxLDAsMC4wMWMxLjMzLDAsMi42Ny0wLjAyLDQsMC4wMQoJCWMwLjQsMC4wMSwwLjM1LTAuMTksMC4zNS0wLjQyYzAuMDMtMi4zOS0wLjI1LTQuNzUtMC45Mi03LjA1Yy0wLjE1LTAuNTItMC4zNy0wLjY0LTAuODktMC41M2MtMy44NSwwLjgtNi40NCwzLjYxLTYuOTgsNy41NQoJCWMtMC4wNSwwLjM2LDAuMDIsMC40NCwwLjM2LDAuNDRDNDkuMjMsOTguNDksNTAuNTksOTguNSw1MS45NCw5OC41eiBNMzUuNzEsOTguNWMxLjI5LDAsMi41OS0wLjAyLDMuODgsMC4wMQoJCWMwLjQ4LDAuMDEsMC41Ni0wLjEyLDAuNDgtMC41OWMtMC4xMy0wLjcyLTAuMy0xLjQyLTAuNTctMi4wOWMtMS4xNy0yLjk1LTMuMzYtNC42OC02LjQ0LTUuMzFjLTAuMjktMC4wNi0wLjYyLTAuMi0wLjc4LDAuMzQKCQljLTAuNzIsMi4zNS0wLjk4LDQuNzQtMSw3LjE4YzAsMC4zNywwLjEzLDAuNDcsMC40OCwwLjQ2QzMzLjA4LDk4LjQ5LDM0LjM5LDk4LjUsMzUuNzEsOTguNXoiLz4KPC9nPgo8L3N2Zz4=', 100);
        add_submenu_page('nddwordfilter', 'Word Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets() {
        wp_enqueue_style('ndd-word-filter-css', plugin_dir_url(__FILE__) . 'styles.css');
    }

    function handleForm() {
        if(wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') AND current_user_can('manage_options')) {
            update_option('plugin_word_filter', sanitize_text_field($_POST['words_to_filter']));
            ?>
            <div class="updated">
                <p>Your filtered words were saved.</p>
            </div>
        <?php } else { ?>
            <div class="error"><p>Sorry you do not have permission to perform that action.</p></div>
        <?php }
    }

    function wordFilterPage() { ?>
        <div class="wrap">
            <h1>NDD Word Filter</h1>
            <?php
                if(isset($_POST['justsubmitted']) && $_POST['justsubmitted'] == 'true') {
                    $this->handleForm();
                }
            ?>
            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce'); ?>
                <label for="words_to_filter"><p>Enter a <strong>comma-separeted</strong> list of words to filter from your site's content.</p></label>
                <div class="word-filter__flex-container">
                    <textarea name="words_to_filter" id="words_to_filter" placeholder="scml, Lx"><?php echo esc_textarea(get_option('plugin_word_filter')) ?></textarea>
                </div>
                
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
    <?php }

    function optionsSubPage() { ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <p>Here are some options for your word filter plugin.</p>
            <form action="options.php" method="POST">
                <?php
                settings_errors(); 
                settings_fields('replacementFields');
                do_settings_sections('word-filter-options');
                submit_button();
                ?>
            </form>
        </div>
    <?php }
}

$nddWordFilterPlugin = new NDD_WordFilterPlugin();