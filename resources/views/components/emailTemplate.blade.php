<style>
    #emailTemplate {
        background:#EEEEEE;
    }
    .email-header{
        height:70px;
        display: flex;
        align-items: center;
        background:#4ABDAC;
    }
    .email-hello {
        font-size: 20px;
        font-weight:bold;
    }
    .email-body {
        max-width: 900px;
        min-height: 300px;
        background:white;
        margin: 20px auto;
        padding:30px;
    }
    .email-footer,
    .email-hello {
        max-width: 900px;
        margin: 30px auto;
    }
    @media (max-width: 900px) {
        .email-body,
        .email-footer,
        .email-hello {
            margin: 20px;
        }
    }
</style>
<div id="emailTemplate">
    <div class="email-header">
    </div>
    <div class="email-hello">
    </div>
    <div class="email-body">
        @yield('email-content')
    </div>
    <div class="email-footer">
    </div>
</div>
