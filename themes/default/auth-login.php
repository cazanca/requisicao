<?php $v->layout("auth"); ?>

<form action="<?= url('/') ?>" method="post" class="ajax">
<?= csrf_input() ?>
<div class="auth-group">
    <label for="email">E-mail</label>
    <input type="email" name="email" id="email" placeholder="Digite o e-mail" autocomplete="off" required>
</div>
<div class="auth-group">
    <label for="password" class="flex">
        Senha
        <a href="forgot.html" class="forgot">Esqueceu a senha?</a>
    </label>
    <input type="password" name="password" id="password" placeholder="Digite a senha:" required>
</div>

<div class="auth-group">
    <label for="remember" class="remember">
        <input type="checkbox"  name="remember" id="remember"> Lembrar dados?
    </label>
</div>

<button type="submit" class="auth-btn">Login</button>
</form>

