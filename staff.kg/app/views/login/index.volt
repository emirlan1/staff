
<body>
<div class="container">
    <h2>Log In</h2>
    <ul class="list-error-group"><?php $this->flash->output(); ?></ul>
    <form method="post">



        <div class="form-group">
            <label for="Login">Login:</label>
            <?php echo $form->render('login'); ?>
        </div>

        <div class="form-group">
            <label for="pwd">Password:</label>
            <?php echo $form->render('password', ['value', 'zzzz']); ?>
        </div>
        <div class="form-group">
            <label for="pwd">CSRF:</label>
            <?php echo $form->render('csrf', ['value' => $this->security->getToken()] ); ?>
        </div>
        <?php echo $form->render('submit'); ?>



    </form>
</div>
</body>
</html>