<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.1.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-account-details-admin--account_number-">
                                <a href="#endpoints-GETapi-v1-account-details-admin--account_number-">Get admin details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-create-account">
                                <a href="#endpoints-POSTapi-v1-create-account">Create a reserved bank account.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-account-details--account_ref-">
                                <a href="#endpoints-GETapi-v1-account-details--account_ref-">Get user details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-verify-account">
                                <a href="#endpoints-POSTapi-v1-verify-account">Verify any bank account.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-get-banks">
                                <a href="#endpoints-GETapi-v1-get-banks">Get Nigerian banks details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-transactions">
                                <a href="#endpoints-GETapi-v1-transactions">Get all transactions</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-transaction--reference-">
                                <a href="#endpoints-GETapi-v1-transaction--reference-">Get one transaction</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-transactions-user--account_ref-">
                                <a href="#endpoints-GETapi-v1-transactions-user--account_ref-">Get all user transactions</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-transaction-status--reference-">
                                <a href="#endpoints-GETapi-v1-transaction-status--reference-">Get transaction status</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-disburse-funds">
                                <a href="#endpoints-POSTapi-v1-disburse-funds">Disburse(withdraw) funds.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-disburse-funds-status--reference-">
                                <a href="#endpoints-GETapi-v1-disburse-funds-status--reference-">Get disburse transaction status</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-pay-online">
                                <a href="#endpoints-POSTapi-v1-pay-online">Topup with card.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-webhook-credit">
                                <a href="#endpoints-POSTapi-v1-webhook-credit">Received funds.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: July 5, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-v1-account-details-admin--account_number-">Get admin details</h2>

<p>
</p>

<p>Replace endpoint with the admin account number. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-account-details-admin--account_number-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/account-details/admin/6318939922" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/account-details/admin/6318939922"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-account-details-admin--account_number-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Admin Account Fetched Successfully&quot;,
    &quot;data&quot;: {
        &quot;accountName&quot;: &quot;Test Account&quot;,
        &quot;accountNumber&quot;: &quot;6318939922&quot;,
        &quot;accountBalance&quot;: {
            &quot;availableBalance&quot;: 4999997000,
            &quot;ledgerBalance&quot;: 4999997000
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-account-details-admin--account_number-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-account-details-admin--account_number-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-account-details-admin--account_number-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-account-details-admin--account_number-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-account-details-admin--account_number-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-account-details-admin--account_number-" data-method="GET"
      data-path="api/v1/account-details/admin/{account_number}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-account-details-admin--account_number-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-account-details-admin--account_number-"
                    onclick="tryItOut('GETapi-v1-account-details-admin--account_number-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-account-details-admin--account_number-"
                    onclick="cancelTryOut('GETapi-v1-account-details-admin--account_number-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-account-details-admin--account_number-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/account-details/admin/{account_number}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-account-details-admin--account_number-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-account-details-admin--account_number-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>account_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="account_number"                data-endpoint="GETapi-v1-account-details-admin--account_number-"
               value="6318939922"
               data-component="url">
    <br>
<p>The account_number of the main monnify account. Example: <code>6318939922</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-v1-create-account">Create a reserved bank account.</h2>

<p>
</p>

<p>Send the required parameters as JSON. If the NIN is valid and everything is okay, you'll get a 201 Created response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-POSTapi-v1-create-account">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/create-account" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"chamberName\": \"John Doe &amp; Sons Chambers\",
    \"email\": \"john.doe@example.com\",
    \"nin\": 5767676767
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/create-account"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "chamberName": "John Doe &amp; Sons Chambers",
    "email": "john.doe@example.com",
    "nin": 5767676767
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-create-account">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Reserved Account Created Successfully&quot;,
    &quot;data&quot;: {
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;chamberName&quot;: &quot;John Doe &amp; Sons Chambers&quot;,
        &quot;email&quot;: &quot;john.doe@example.com&quot;,
        &quot;nin&quot;: 5767676767,
        &quot;bankDetails&quot;: {
            &quot;accountRef&quot;: &quot;cliApp684404f8964ec&quot;,
            &quot;accountNumber&quot;: 3318057324,
            &quot;accountName&quot;: &quot;JOHN DOE &amp; SONS CHAMBERS&quot;,
            &quot;bankName&quot;: &quot;Wema bank&quot;,
            &quot;accountBal&quot;: 0
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-create-account" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-create-account"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-create-account"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-create-account" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-create-account">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-create-account" data-method="POST"
      data-path="api/v1/create-account"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-create-account', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-create-account"
                    onclick="tryItOut('POSTapi-v1-create-account');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-create-account"
                    onclick="cancelTryOut('POSTapi-v1-create-account');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-create-account"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/create-account</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-create-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-create-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-create-account"
               value="John Doe"
               data-component="body">
    <br>
<p>Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>chamberName</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="chamberName"                data-endpoint="POSTapi-v1-create-account"
               value="John Doe & Sons Chambers"
               data-component="body">
    <br>
<p>Example: <code>John Doe &amp; Sons Chambers</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-create-account"
               value="john.doe@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Example: <code>john.doe@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nin</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="nin"                data-endpoint="POSTapi-v1-create-account"
               value="5767676767"
               data-component="body">
    <br>
<p>Example: <code>5767676767</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-v1-account-details--account_ref-">Get user details</h2>

<p>
</p>

<p>Replace endpoint with the user account ref. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-account-details--account_ref-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/account-details/cliApp684404f8964ec" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/account-details/cliApp684404f8964ec"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-account-details--account_ref-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Reserved Account Fetched Successfully&quot;,
    &quot;data&quot;: {
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;chamberName&quot;: &quot;John Doe &amp; Sons Chambers&quot;,
        &quot;email&quot;: &quot;john.doe@example.com&quot;,
        &quot;nin&quot;: 5767676767,
        &quot;bankDetails&quot;: {
            &quot;accountRef&quot;: &quot;cliApp684404f8964ec&quot;,
            &quot;accountNumber&quot;: 3318057324,
            &quot;accountName&quot;: &quot;JOHN DOE &amp; SONS CHAMBERS&quot;,
            &quot;bankName&quot;: &quot;Wema bank&quot;,
            &quot;accountBal&quot;: 0
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-account-details--account_ref-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-account-details--account_ref-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-account-details--account_ref-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-account-details--account_ref-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-account-details--account_ref-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-account-details--account_ref-" data-method="GET"
      data-path="api/v1/account-details/{account_ref}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-account-details--account_ref-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-account-details--account_ref-"
                    onclick="tryItOut('GETapi-v1-account-details--account_ref-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-account-details--account_ref-"
                    onclick="cancelTryOut('GETapi-v1-account-details--account_ref-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-account-details--account_ref-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/account-details/{account_ref}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-account-details--account_ref-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-account-details--account_ref-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>account_ref</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="account_ref"                data-endpoint="GETapi-v1-account-details--account_ref-"
               value="cliApp684404f8964ec"
               data-component="url">
    <br>
<p>The account_ref of the user's monnify account. Example: <code>cliApp684404f8964ec</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-v1-verify-account">Verify any bank account.</h2>

<p>
</p>

<p>Send the required parameters as JSON. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-POSTapi-v1-verify-account">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/verify-account" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"accountNumber\": 3434343434,
    \"bankCode\": 35
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/verify-account"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "accountNumber": 3434343434,
    "bankCode": 35
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-verify-account">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Account Details Valid&quot;,
    &quot;data&quot;: {
        &quot;accountName&quot;: &quot;IFEANYI GODSWILL OKPANKU&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-verify-account" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-verify-account"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-verify-account"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-verify-account" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-verify-account">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-verify-account" data-method="POST"
      data-path="api/v1/verify-account"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-verify-account', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-verify-account"
                    onclick="tryItOut('POSTapi-v1-verify-account');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-verify-account"
                    onclick="cancelTryOut('POSTapi-v1-verify-account');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-verify-account"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/verify-account</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-verify-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-verify-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>accountNumber</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="accountNumber"                data-endpoint="POSTapi-v1-verify-account"
               value="3434343434"
               data-component="body">
    <br>
<p>Example: <code>3434343434</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>bankCode</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="bankCode"                data-endpoint="POSTapi-v1-verify-account"
               value="35"
               data-component="body">
    <br>
<p>Example: <code>35</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-v1-get-banks">Get Nigerian banks details</h2>

<p>
</p>

<p>If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-get-banks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/get-banks" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/get-banks"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-get-banks">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Banks Details Fetched Successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;name&quot;: &quot;9JAPAY MICROFINANCE BANK&quot;,
            &quot;code&quot;: &quot;090629&quot;,
            &quot;ussdTemplate&quot;: null,
            &quot;baseUssdCode&quot;: null,
            &quot;transferUssdTemplate&quot;: null,
            &quot;bankId&quot;: null,
            &quot;nipBankCode&quot;: &quot;090629&quot;
        },
        {
            &quot;name&quot;: &quot;Access bank&quot;,
            &quot;code&quot;: &quot;044&quot;,
            &quot;ussdTemplate&quot;: &quot;*901*Amount*AccountNumber#&quot;,
            &quot;baseUssdCode&quot;: &quot;*901#&quot;,
            &quot;transferUssdTemplate&quot;: &quot;*901*AccountNumber#&quot;,
            &quot;bankId&quot;: null,
            &quot;nipBankCode&quot;: &quot;000014&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-get-banks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-get-banks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-get-banks"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-get-banks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-get-banks">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-get-banks" data-method="GET"
      data-path="api/v1/get-banks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-get-banks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-get-banks"
                    onclick="tryItOut('GETapi-v1-get-banks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-get-banks"
                    onclick="cancelTryOut('GETapi-v1-get-banks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-get-banks"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/get-banks</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-get-banks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-get-banks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-transactions">Get all transactions</h2>

<p>
</p>

<p>If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-transactions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/transactions" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/transactions"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-transactions">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Transaction(s) Fetched Successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;accountRef&quot;: &quot;cliApp68400ed1b4b25&quot;,
            &quot;accountName&quot;: &quot;KIN&quot;,
            &quot;accountNumber&quot;: 3396488285,
            &quot;bankName&quot;: &quot;Wema bank&quot;,
            &quot;transactionDetails&quot;: {
                &quot;type&quot;: &quot;credit&quot;,
                &quot;amount&quot;: &quot;100.00&quot;,
                &quot;narration&quot;: &quot;Loan&quot;,
                &quot;reference&quot;: &quot;cliApp68400ed1b4b25-7544734744&quot;,
                &quot;isCompleted&quot;: &quot;FAILED&quot;,
                &quot;senderAccountName&quot;: &quot;John Obi&quot;,
                &quot;senderAccountNumber&quot;: &quot;4574757787&quot;,
                &quot;senderBankName&quot;: &quot;035&quot;
            },
            &quot;createdAt&quot;: &quot;2025-06-04T09:16:05.000000Z&quot;
        },
        {
            &quot;accountRef&quot;: &quot;cliApp68400ed1b4b25&quot;,
            &quot;accountName&quot;: &quot;KIN&quot;,
            &quot;accountNumber&quot;: 3396488285,
            &quot;bankName&quot;: &quot;Wema bank&quot;,
            &quot;transactionDetails&quot;: {
                &quot;type&quot;: &quot;debit&quot;,
                &quot;amount&quot;: &quot;2000.00&quot;,
                &quot;totalFee&quot;: &quot;10.00&quot;,
                &quot;narration&quot;: &quot;Gift&quot;,
                &quot;reference&quot;: &quot;cliApp68400ed1b4b25-734373733733&quot;,
                &quot;isCompleted&quot;: &quot;PAID&quot;,
                &quot;receiverAccountName&quot;: &quot;IFEANYI OKPANKU&quot;,
                &quot;receiverAccountNumber&quot;: &quot;0691571803&quot;,
                &quot;receiverBankName&quot;: &quot;Access Bank&quot;
            },
            &quot;createdAt&quot;: &quot;2025-06-04T09:16:05.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-transactions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-transactions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-transactions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-transactions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-transactions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-transactions" data-method="GET"
      data-path="api/v1/transactions"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-transactions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-transactions"
                    onclick="tryItOut('GETapi-v1-transactions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-transactions"
                    onclick="cancelTryOut('GETapi-v1-transactions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-transactions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/transactions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-transactions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-transactions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-transaction--reference-">Get one transaction</h2>

<p>
</p>

<p>Replace endpoint with the transaction reference. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-transaction--reference-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/transaction/cliApp68400ed1b4b25-7544734744" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/transaction/cliApp68400ed1b4b25-7544734744"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-transaction--reference-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Transaction Fetched Successfully&quot;,
    &quot;data&quot;: {
        &quot;accountRef&quot;: &quot;cliApp68400ed1b4b25&quot;,
        &quot;accountName&quot;: &quot;KIN&quot;,
        &quot;accountNumber&quot;: 3396488285,
        &quot;bankName&quot;: &quot;Wema bank&quot;,
        &quot;transactionDetails&quot;: {
            &quot;type&quot;: &quot;credit&quot;,
            &quot;amount&quot;: &quot;100.00&quot;,
            &quot;narration&quot;: &quot;Loan&quot;,
            &quot;reference&quot;: &quot;cliApp68400ed1b4b25-7544734744&quot;,
            &quot;isCompleted&quot;: &quot;FAILED&quot;,
            &quot;senderAccountName&quot;: &quot;John Obi&quot;,
            &quot;senderAccountNumber&quot;: &quot;4574757787&quot;,
            &quot;senderBankName&quot;: &quot;035&quot;
        },
        &quot;createdAt&quot;: &quot;2025-06-04T09:16:05.000000Z&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-transaction--reference-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-transaction--reference-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-transaction--reference-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-transaction--reference-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-transaction--reference-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-transaction--reference-" data-method="GET"
      data-path="api/v1/transaction/{reference}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-transaction--reference-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-transaction--reference-"
                    onclick="tryItOut('GETapi-v1-transaction--reference-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-transaction--reference-"
                    onclick="cancelTryOut('GETapi-v1-transaction--reference-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-transaction--reference-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/transaction/{reference}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-transaction--reference-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-transaction--reference-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="GETapi-v1-transaction--reference-"
               value="cliApp68400ed1b4b25-7544734744"
               data-component="url">
    <br>
<p>The reference of the transaction. Example: <code>cliApp68400ed1b4b25-7544734744</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-transactions-user--account_ref-">Get all user transactions</h2>

<p>
</p>

<p>Replace endpoint with the user's account ref. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-transactions-user--account_ref-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/transactions/user/cliApp68400ed1b4b25" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/transactions/user/cliApp68400ed1b4b25"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-transactions-user--account_ref-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Transaction(s) Fetched Successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;accountRef&quot;: &quot;cliApp68400ed1b4b25&quot;,
            &quot;accountName&quot;: &quot;KIN&quot;,
            &quot;accountNumber&quot;: 3396488285,
            &quot;bankName&quot;: &quot;Wema bank&quot;,
            &quot;transactionDetails&quot;: {
                &quot;type&quot;: &quot;credit&quot;,
                &quot;amount&quot;: &quot;100.00&quot;,
                &quot;narration&quot;: &quot;Loan&quot;,
                &quot;reference&quot;: &quot;cliApp68400ed1b4b25-7544734744&quot;,
                &quot;isCompleted&quot;: &quot;FAILED&quot;,
                &quot;senderAccountName&quot;: &quot;John Obi&quot;,
                &quot;senderAccountNumber&quot;: &quot;4574757787&quot;,
                &quot;senderBankName&quot;: &quot;035&quot;
            },
            &quot;createdAt&quot;: &quot;2025-06-04T09:16:05.000000Z&quot;
        },
        {
            &quot;accountRef&quot;: &quot;cliApp68400ed1b4b25&quot;,
            &quot;accountName&quot;: &quot;KIN&quot;,
            &quot;accountNumber&quot;: 3396488285,
            &quot;bankName&quot;: &quot;Wema bank&quot;,
            &quot;transactionDetails&quot;: {
                &quot;type&quot;: &quot;debit&quot;,
                &quot;amount&quot;: &quot;2000.00&quot;,
                &quot;totalFee&quot;: &quot;10.00&quot;,
                &quot;narration&quot;: &quot;Gift&quot;,
                &quot;reference&quot;: &quot;cliApp68400ed1b4b25-734373733733&quot;,
                &quot;isCompleted&quot;: &quot;PAID&quot;,
                &quot;receiverAccountName&quot;: &quot;IFEANYI OKPANKU&quot;,
                &quot;receiverAccountNumber&quot;: &quot;0691571803&quot;,
                &quot;receiverBankName&quot;: &quot;Access Bank&quot;
            },
            &quot;createdAt&quot;: &quot;2025-06-04T09:16:05.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-transactions-user--account_ref-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-transactions-user--account_ref-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-transactions-user--account_ref-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-transactions-user--account_ref-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-transactions-user--account_ref-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-transactions-user--account_ref-" data-method="GET"
      data-path="api/v1/transactions/user/{account_ref}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-transactions-user--account_ref-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-transactions-user--account_ref-"
                    onclick="tryItOut('GETapi-v1-transactions-user--account_ref-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-transactions-user--account_ref-"
                    onclick="cancelTryOut('GETapi-v1-transactions-user--account_ref-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-transactions-user--account_ref-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/transactions/user/{account_ref}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-transactions-user--account_ref-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-transactions-user--account_ref-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>account_ref</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="account_ref"                data-endpoint="GETapi-v1-transactions-user--account_ref-"
               value="cliApp68400ed1b4b25"
               data-component="url">
    <br>
<p>The user's account_ref. Example: <code>cliApp68400ed1b4b25</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-transaction-status--reference-">Get transaction status</h2>

<p>
</p>

<p>Replace endpoint with the transaction reference. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-transaction-status--reference-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/transaction/status/MNFY|02|20250704161048|000089" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/transaction/status/MNFY|02|20250704161048|000089"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-transaction-status--reference-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
      &quot;status&quot;: &quot;success&quot;,
      &quot;message&quot;: &quot;Transaction Status Fetched Successfully&quot;,
      &quot;data&quot;: {
           &quot;isCompleted&quot;: &quot;PAID&quot;,
      }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-transaction-status--reference-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-transaction-status--reference-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-transaction-status--reference-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-transaction-status--reference-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-transaction-status--reference-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-transaction-status--reference-" data-method="GET"
      data-path="api/v1/transaction/status/{reference}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-transaction-status--reference-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-transaction-status--reference-"
                    onclick="tryItOut('GETapi-v1-transaction-status--reference-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-transaction-status--reference-"
                    onclick="cancelTryOut('GETapi-v1-transaction-status--reference-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-transaction-status--reference-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/transaction/status/{reference}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-transaction-status--reference-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-transaction-status--reference-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="GETapi-v1-transaction-status--reference-"
               value="MNFY|02|20250704161048|000089"
               data-component="url">
    <br>
<p>The reference of the transaction. Example: <code>MNFY|02|20250704161048|000089</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-v1-disburse-funds">Disburse(withdraw) funds.</h2>

<p>
</p>

<p>Send the required parameters as JSON. If everything is okay, you'll get a 201 Created response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-POSTapi-v1-disburse-funds">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/disburse-funds" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"accountRef\": \"cliApp68400ed1b4b25\",
    \"amount\": 2000,
    \"narration\": \"This is a gift money withdrawal\",
    \"destinationBankCode\": 44,
    \"destinationAccountNumber\": 69157103
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/disburse-funds"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "accountRef": "cliApp68400ed1b4b25",
    "amount": 2000,
    "narration": "This is a gift money withdrawal",
    "destinationBankCode": 44,
    "destinationAccountNumber": 69157103
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-disburse-funds">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
      &quot;status&quot;: &quot;success&quot;,
      &quot;message&quot;: &quot;Funds Successfully Disbursed&quot;,
      &quot;data&quot;: {
          &quot;amount&quot;: 350,,
          &quot;reference&quot;: &quot;cliApp6867b27d3a2c3-1751707790&quot;,
          &quot;status&quot;: &quot;PENDING_AUTHORIZATION&quot;,
          &quot;dateCreated&quot;: &quot;2025-07-05T09:29:51.274+00:00&quot;,
          &quot;totalFee&quot;: 35,
          &quot;destinationBankName&quot;: &quot;Access bank&quot;,
          &quot;destinationAccountNumber&quot;: &quot;0691571803&quot;,
          &quot;destinationBankCode&quot;: &quot;044&quot;
      }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-disburse-funds" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-disburse-funds"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-disburse-funds"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-disburse-funds" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-disburse-funds">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-disburse-funds" data-method="POST"
      data-path="api/v1/disburse-funds"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-disburse-funds', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-disburse-funds"
                    onclick="tryItOut('POSTapi-v1-disburse-funds');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-disburse-funds"
                    onclick="cancelTryOut('POSTapi-v1-disburse-funds');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-disburse-funds"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/disburse-funds</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-disburse-funds"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-disburse-funds"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>accountRef</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="accountRef"                data-endpoint="POSTapi-v1-disburse-funds"
               value="cliApp68400ed1b4b25"
               data-component="body">
    <br>
<p>Example: <code>cliApp68400ed1b4b25</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-v1-disburse-funds"
               value="2000"
               data-component="body">
    <br>
<p>Must match the regex /^\d+(.\d{1,2})?$/. Example: <code>2000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>narration</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="narration"                data-endpoint="POSTapi-v1-disburse-funds"
               value="This is a gift money withdrawal"
               data-component="body">
    <br>
<p>Example: <code>This is a gift money withdrawal</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destinationBankCode</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="destinationBankCode"                data-endpoint="POSTapi-v1-disburse-funds"
               value="44"
               data-component="body">
    <br>
<p>Example: <code>44</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destinationAccountNumber</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="destinationAccountNumber"                data-endpoint="POSTapi-v1-disburse-funds"
               value="69157103"
               data-component="body">
    <br>
<p>Example: <code>69157103</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-v1-disburse-funds-status--reference-">Get disburse transaction status</h2>

<p>
</p>

<p>Replace endpoint with the disburse transaction reference. If everything is okay, you'll get a 200 OK response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-GETapi-v1-disburse-funds-status--reference-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/disburse-funds/status/cliApp68400ed1b4b25-7544734744" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/disburse-funds/status/cliApp68400ed1b4b25-7544734744"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-disburse-funds-status--reference-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
      &quot;status&quot;: &quot;success&quot;,
      &quot;message&quot;: &quot;Transaction Status Fetched Successfully&quot;,
      &quot;data&quot;: {
           &quot;isCompleted&quot;: &quot;PAID&quot;,
      }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-disburse-funds-status--reference-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-disburse-funds-status--reference-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-disburse-funds-status--reference-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-disburse-funds-status--reference-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-disburse-funds-status--reference-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-disburse-funds-status--reference-" data-method="GET"
      data-path="api/v1/disburse-funds/status/{reference}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-disburse-funds-status--reference-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-disburse-funds-status--reference-"
                    onclick="tryItOut('GETapi-v1-disburse-funds-status--reference-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-disburse-funds-status--reference-"
                    onclick="cancelTryOut('GETapi-v1-disburse-funds-status--reference-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-disburse-funds-status--reference-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/disburse-funds/status/{reference}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-disburse-funds-status--reference-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-disburse-funds-status--reference-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="GETapi-v1-disburse-funds-status--reference-"
               value="cliApp68400ed1b4b25-7544734744"
               data-component="url">
    <br>
<p>The reference of the disburse transaction. Example: <code>cliApp68400ed1b4b25-7544734744</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-v1-pay-online">Topup with card.</h2>

<p>
</p>

<p>Send the required parameters as JSON. If everything is okay, you'll get a 200 OK response.</p>
<p>Then redirect user to the checkout URL to complete payment.</p>

<span id="example-requests-POSTapi-v1-pay-online">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/pay-online" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"accountRef\": \"cliApp68400ed1b4b25\",
    \"amount\": 2000,
    \"description\": \"This is a gift money deposit\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/pay-online"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "accountRef": "cliApp68400ed1b4b25",
    "amount": 2000,
    "description": "This is a gift money deposit"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-pay-online">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
      &quot;status&quot;: &quot;success&quot;,
      &quot;message&quot;: &quot;Successful, Redirect To Checkout&quot;,
      &quot;data&quot;: {
          &quot;checkoutURL&quot;: &quot;https://sandbox.sdk.monnify.com/checkout/MNFY|08|20250705105040|000183&quot;,
      }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-pay-online" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-pay-online"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-pay-online"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-pay-online" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-pay-online">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-pay-online" data-method="POST"
      data-path="api/v1/pay-online"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-pay-online', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-pay-online"
                    onclick="tryItOut('POSTapi-v1-pay-online');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-pay-online"
                    onclick="cancelTryOut('POSTapi-v1-pay-online');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-pay-online"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/pay-online</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-pay-online"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-pay-online"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>accountRef</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="accountRef"                data-endpoint="POSTapi-v1-pay-online"
               value="cliApp68400ed1b4b25"
               data-component="body">
    <br>
<p>Example: <code>cliApp68400ed1b4b25</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-v1-pay-online"
               value="2000"
               data-component="body">
    <br>
<p>Must match the regex /^\d+(.\d{1,2})?$/. Example: <code>2000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-pay-online"
               value="This is a gift money deposit"
               data-component="body">
    <br>
<p>Example: <code>This is a gift money deposit</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-v1-webhook-credit">Received funds.</h2>

<p>
</p>

<p>Notification is sent to this endpoint whenever a user receives a topup either by transfer or card. If everything is okay, you'll get a 201 Created response.</p>
<p>Otherwise, the request will fail with an error, and a response listing the failed services.</p>

<span id="example-requests-POSTapi-v1-webhook-credit">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/webhook/credit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/webhook/credit"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-webhook-credit">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;message&quot;: &quot;Account Credited N1000&quot;,
    &quot;data&quot;: {
        &quot;accountRef&quot;: &quot;cliApp68400ed1b4b25&quot;,
        &quot;accountName&quot;: &quot;KIN&quot;,
        &quot;accountNumber&quot;: 3396488285,
        &quot;bankName&quot;: &quot;Wema bank&quot;,
        &quot;transactionDetails&quot;: {
            &quot;type&quot;: &quot;credit&quot;,
            &quot;amount&quot;: &quot;1000.00&quot;,
            &quot;narration&quot;: &quot;Loan&quot;,
            &quot;reference&quot;: &quot;cliApp68400ed1b4b25-7544734744&quot;,
            &quot;isCompleted&quot;: &quot;FAILED&quot;,
            &quot;senderAccountName&quot;: &quot;John Obi&quot;,
            &quot;senderAccountNumber&quot;: &quot;4574757787&quot;,
            &quot;senderBankName&quot;: &quot;035&quot;
        },
        &quot;createdAt&quot;: &quot;2025-06-04T09:16:05.000000Z&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-webhook-credit" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-webhook-credit"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-webhook-credit"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-webhook-credit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-webhook-credit">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-webhook-credit" data-method="POST"
      data-path="api/v1/webhook/credit"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-webhook-credit', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-webhook-credit"
                    onclick="tryItOut('POSTapi-v1-webhook-credit');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-webhook-credit"
                    onclick="cancelTryOut('POSTapi-v1-webhook-credit');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-webhook-credit"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/webhook/credit</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-webhook-credit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-webhook-credit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
