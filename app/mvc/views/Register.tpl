<!DOCTYPE html>
<html>

  <head>
    <title>Register</title>
  </head>

  <body>
    <div style="margin:auto;border:3px solid purple;width:30%;padding:25pt;position:relative;align-items:center;justify-content:center;">
      <h2>Create new account</h2>
      <a href="/">&lt; Back to index page</a><br>
      <form method="post" action="/">
        <input type="hidden" name="action" value="register"/>
        Login:<br> <input type="text" name="login"/><br>
        Pwd: <br> <input type="password" name="pwd"/><br>
        <input type="submit" value="Sign Up"/>
      </form>
    </div>
  </body>

</html>
