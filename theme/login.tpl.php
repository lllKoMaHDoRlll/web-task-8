
<title>Login Page</title>

<header>
    <img src="assets/images/cat_logo.png" id="logo" alt="Logo">
    <h1 id="name">Cofee Cat</h1>
</header>
<div id="content">
    <section id="form" class="container">
        <h2>Form</h2>
        <form action="/api/login" method="POST">
            <label>Login:
                <input name="field-login" placeholder="Your login" type="text">
            </label>
            <label>Password:
                <input name="field-password" placeholder="Your password" type="password">
            </label>
            <label class="submit-button"><input type="submit"></label>
        </form>
    </section>
</div>