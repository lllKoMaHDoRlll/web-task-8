<?php 
include_once("scripts/utils.php");
?>

<title>Form page</title>

<header>
    <img src="/assets/images/cat_logo.png" id="logo" alt="Logo">
    <h1 id="name">Cofee Cat</h1>
</header>
<div id="content">
    <section id="login-status-container" class="container" <?php echo isset($_COOKIE[session_name()])? '' : 'style="display: none"'; ?>>
        <p>
            You have been signed in as <?php echo !empty($_SESSION['login'])? $_SESSION['login'] : ''; ?>
        </p>
        <button type="button" value="<?php echo session_name(); ?>" id="logout-button">Log out</button>
    </section>
    <section id="login-data-container" class="container" <?php echo !empty($_COOKIE['password'])? '': 'style="display: none"' ?>>
        <p class="text-light">
            You can <a href="./login">login</a> with login: <?php echo empty($_COOKIE['login'])? '': sanitize($_COOKIE['login']); ?> and password: <?php echo empty($_COOKIE['password'])? '': sanitize($_COOKIE['password']);?>.
        </p>
    </section>
    <section id="form" class="container">
        <h2>Form</h2>
        <form action="/api/form" method="POST" id="submission-form">
            <?php
                print isset($_SESSION['csrf_token']) ? '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">' : '';
                print isset($_SESSION['user_id'])? '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">' : '';
            ?>
            <label>Name:
                <input name="field-name" placeholder="Your name" type="text" <?php print empty($_COOKIE["field-name-error"]) ? '' : 'class="err_input"'; ?>
                    value="<?php print $c['values']["name"] ?>">
                <span id="field-name-err-desc" class="err_desc" <?php print empty($_COOKIE["field-name-error"]) ? 'style="display:none;"' : '' ?>>Please fill name field correctly</span>
            </label>

            <label>Phone:
                <input name="field-phone" placeholder="Your phone number" type="tel" <?php print empty($_COOKIE["field-phone-error"]) ? '' : 'class="err_input"'; ?>
                    value="<?php print $c['values']["phone"]; ?>">
                <span id="field-phone-err-desc" class="err_desc" <?php print empty($_COOKIE["field-phone-error"]) ? 'style="display:none;"' : '' ?>>Please fill phone field correctly</span>
            </label>

            <label>Email:
                <input name="field-email" placeholder="Your email adress" type="email" <?php print empty($_COOKIE["field-email-error"]) ? '' : 'class="err_input"'; ?>
                    value="<?php print $c['values']['email']; ?>">
                <span id="field-email-err-desc" class="err_desc" <?php print empty($_COOKIE["field-email-error"]) ? 'style="display:none;"' : '' ?>>Please fill email field correctly</span>
            </label>

            <label>Date of birth:
                <input name="field-date" type="date" <?php print empty($_COOKIE["field-date-error"]) ? '' : 'class="err_input"'; ?>
                    value="<?php print $c['values']["date"]; ?>">
                <span id="field-date-err-desc" class="err_desc" <?php print empty($_COOKIE["field-date-error"]) ? 'style="display:none;"' : '' ?>>Please fill birthday date field correctly</span>
            </label>

            <p>Gender:</p>
            <label class="radio-gender">
                <input name="field-gender" type="radio" value="male" <?php print $c['values']["gender"] == '1' ? 'checked' : ''; ?>>Male
            </label>
            <label class="radio-gender">
                <input name="field-gender" type="radio" value="female" <?php print $c['values']["gender"] == '0' ? 'checked' : ''; ?>>Female
            </label>
            <span id="field-gender-err-desc" class="err_desc" <?php print empty($_COOKIE["field-gender-error"]) ? 'style="display:none;"' : '' ?>>Please fill gender field correctly</span>

            <label>Favorite PL:
                <select name="field-pl[]" multiple="multiple" <?php print empty($_COOKIE["field-pl-error"]) ? '' : 'class="err_input"'; ?>>
                    <option value="pascal" <?php print str_contains($c['values']["fpls"], '@pascal@') ? 'selected' : ''; ?>>Pascal</option>
                    <option value="c" <?php print str_contains($c['values']["fpls"], '@c@') ? 'selected' : ''; ?>>C</option>
                    <option value="cpp" <?php print str_contains($c['values']["fpls"], '@cpp@') ? 'selected' : ''; ?>>C++</option>
                    <option value="js" <?php print str_contains($c['values']["fpls"], '@js@') ? 'selected' : ''; ?>>JavaScript</option>
                    <option value="php" <?php print str_contains($c['values']["fpls"], '@php@') ? 'selected' : ''; ?>>PHP</option>
                    <option value="python" <?php print str_contains($c['values']["fpls"], '@python@') ? 'selected' : ''; ?>>Python</option>
                    <option value="java" <?php print str_contains($c['values']["fpls"], '@java@') ? 'selected' : ''; ?>>Java</option>
                    <option value="haskel" <?php print str_contains($c['values']["fpls"], '@haskel@') ? 'selected' : ''; ?>>Haskel</option>
                    <option value="clojure" <?php print str_contains($c['values']["fpls"], '@clojure@') ? 'selected' : ''; ?>>Clojure</option>
                    <option value="prolog" <?php print str_contains($c['values']["fpls"], '@prolog@') ? 'selected' : ''; ?>>Prolog</option>
                    <option value="scala" <?php print str_contains($c['values']["fpls"], '@scala@') ? 'selected' : ''; ?>>Scala</option>
                </select>
                <span id="field-fpls-err-desc" class="err_desc" <?php print empty($_COOKIE["field-pl-error"]) ? 'style="display:none;"' : '' ?>>Please fill fpl field correctly</span>
            </label>

            <label>BIO:
                <textarea name="field-bio" <?php print empty($_COOKIE["field-bio-error"]) ? '' : 'class="err_input"'; ?>><?php print $c['values']["bio"]; ?></textarea>
                <span id="field-bio-err-desc" class="err_desc" <?php print empty($_COOKIE["field-bio-error"]) ? 'style="display:none;"' : '' ?>>Please fill bio field correctly</span>
            </label>

            <label id="chkbox-label">
                <input type="checkbox" name="check-accept" value="accepted">
                Accept
                <span id="field-acception-err-desc" class="err_desc" <?php print empty($_COOKIE["field-accept-error"]) ? 'style="display:none;"' : '' ?>>Please accept Privacy Politics.</span>
            </label>

            <label class="submit-button"><input type="submit"></label>
        </form>
    </section>
</div>
