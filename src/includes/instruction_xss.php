<h4 class="text-wwu-green">Cross-Site Scripting (XSS)</h4>
<p>
    This website yields security vulnerabilities that can be abused for XSS.
    You are not allowed to exploit these vulnerabilities in any other way than intended for your exercises.
    <br>
    There are two XSS challenges. The first one is a reflective XSS and simulates a search field.
    The second challenge simulates a product page with a comment field.<br>
</p>
<p>
    <b>Task: Reflective XSS</b><br>
    You can abuse the search field to read out a user's session ID that is stored in a cookie.<br>
    To do this you will have to create a JavaScript code snippet that displays the document's cookie.<br>
    Note or copy the obtained session ID. The site will detect if you found the session ID and will either show you a popup where you can enter the session ID or display a button beneath the search results to trigger said popup manually.
    This depends on the way you obtained the session ID.
</p>
<p>
    <b>Task: Stored XSS</b><br>
    The product reviews are stored in a database. Your task is to create a JavaScript code snippet that simulates a cookie stealing attack.<br>
    Luckily, you are a very well prepared attacker and you have already created a PHP page <em>cookie.php</em> in the root directory of your webserver <em>evil.domain</em>.
    You have planed to obtain the session ID cookies for every visitor of the product review page by passing them as a GET variable to your PHP page. As a reminder, a GET variable is simply appended to the end of an URL with a ? followed by its name and its value (e.g. example.com?name=value).
    To make things easier, you only have to show a JavaScript popup to the visitors with the link to your PHP page followed by their session ID as a GET variable. As soon as someone visits the site you will receive a popup with their session ID and an option to steal their session. This will probably happen rather quickly since this is a VERY popular site.
    If you have successfully stolen the session of your victim, you should manually manipulate his/her shopping cart by adding a Banana Slicer. Everyone should have one these days!
</p>
<br>