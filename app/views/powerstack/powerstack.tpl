<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Ubuntu);
body {
    font-family: 'Ubuntu', sans-serif;
    background-color: #2A333C;
    color: #E36B23;
}
p, pre {
    color: #ECE9D7;
}
pre {
    background-color: #566577;
    padding: 10px;
    border-radius: 10px;
}
a {
    color: #E36B23;
}
#main {
    width: 960px;
    margin-left: auto;
    margin-right: auto;
}
span.comment {
    color: #48AB4B;
}
span.var {
    color: #EDEB7B;
}
span.obj {
    color: #DEDA00;
}
span.keyword {
    color: #F0980C;
}
span.string {
    /*color: #AD5858;*/
    color: #6E3131;
}
</style>
<div id="main">
    <h1>Welcome to Powerstack</h1>
    <p>
        To start building your application and defining routes
        edit the app/app.php file.<br />
        This file is the main application file, here you define
        your routes and what they do.
    </p>
    <h2>app.php</h2>
    <p>Below shows the current contents of the app/app.php file.</p>
    <code>
    <pre>
<span class="comment">// This is the main route that display this page.</span>
<span class="var">$app</span><span class="obj">-&gt;</span>get(<span class="string">'/'</span>, <span class="keyword">function</span>(<span class="var">$request</span>, <span class="var">$params</span>) <span class="keyword">{</span>
    template(<span class="string">'powerstack/powerstack.tpl'</span>);
<span class="keyword">}</span>);
    </pre>
    </code>
    <p>More documentation can be found here: <a href="https://github.com/powerstack/powerstack/wiki">https://github.com/powerstack/powerstack/wiki</a></p>
</div>
