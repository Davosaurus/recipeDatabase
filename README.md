# The Recipe Database
<h3>Installation</h3>
<ul>
<li>You will need to install <a href="https://www.apachefriends.org/">XAMPP</a> to manage the SQL and web servers (only Apache and mySQL are required)</li>
<li>Navigate to the source directory of XAMPP (C:\xampp\htdocs by default in windows)</li>
<li>Clone the repository in that directory</li>
<li><i>Optional: <a href="https://www.google.com/search?ei=7prZXeGrD8TYtAWLya64DQ&q=how+to+set+up+a+static+ip&oq=how+to+set+up+a+static+ip&gs_l=psy-ab.3..0i10l7.693540.697665..697922...0.1..0.153.3272.0j25......0....1..gws-wiz.......0i71j0i67j0i273j0j0i131.V9TiHXnsnkY&ved=0ahUKEwjh2reimoHmAhVELK0KHYukC9cQ4dUDCAo&uact=5">Set up a static IP</a> so it's easier for computers on your home network to connect. (Note: this is unnecessary if you are only accessing the DB through the host PC)</i></li>
</ul>
<h3>Running the Database</h3>
<ul>
<li>Launch the XAMPP Control Panel. You can optionally set it up so that it launches to the system tray instead of opening a window</li>
<li>From the control panel, start the mySQL and Apache servers</li>
<li>The database is now up and running!</li>
</ul>
<h3>Accessing the Database</h3>
<ul>
<li>If you are accessing the database from the host PC, simply head to <a href="http://localhost/recipeDatabase/getrecipeinfo.php">this URL.</a> You can favorite it if you want, that link will always point to the database if it's running on your machine</li>
<li>If you want the database to be available to other computers on your home network, you'll need to replace "localhost" in the URL with the local IP address of the host PC</li>
</ul>
