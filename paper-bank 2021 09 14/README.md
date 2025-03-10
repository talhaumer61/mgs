# Paper Bank

This is a Paper Bank to Generate Random Question Paper from already made questions based on the class, subject, chapter and subjective and objective types.
This project is underdevelopment.
Use it at your own risk.

## config
Edit <code>/public/.htaccess</code> file to your root url name 
for example if code is inside a folder called <code>demo</code> then replace the
<code>/public/.htaccess</code> replace <code>  RewriteBase /mgs/public</code> with <code>  RewriteBase /%YOURFOLDERNAME-HERE%/public</code>
like <code>RewriteBase /demo/public</code>

also create a <code>config.php</code> file in the root directory

and configure according to this layout.

<code>$_ENV["URLROOT"] = "YOUR ROOT URL";  </code> <br>
<code>$_ENV["HOST"] = '127.0.0.1';  </code><br>
<code>$_ENV["DBNAME"] = 'mgs2'; </code><br>
<code>$_ENV["USERNAME"] = 'root'; </code><br>
<code>$_ENV["PASSWORD"] = '';  </code><br>
<code>$_ENV["ENV"] = 'development'; </code><br>
<code>$_ENV["DEFAULT_CONTROLLER"] = 'Dashboard'; </code><br>
<code>$_ENV["DEFAULT_METHOD"] = 'index'; </code><br>
<code>$_ENV['LIMIT'] = 10; </code><br>
<code>$_ENV['DBDRIVER'] = 'mysql'; </code><br>
<code>$_ENV['SITENAME'] = "School Management System"; </code><br>

