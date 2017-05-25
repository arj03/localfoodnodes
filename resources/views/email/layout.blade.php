<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Local Food Nodes - {{ $title or '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style type="text/css">
        body {
            margin:0;
            padding:0;
        }
        img {
            border:0 none;
            height:auto;
            line-height:100%;
            outline:none;
            text-decoration:none;
        }
        a {
            color: #188092;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        a img {
            border:0 none;
        }
        .imageFix {
            display:block;
        }
        table, td {
            border-collapse:collapse;
        }
        #bodyTable {
            height:100% !important;
            margin:0;
            padding:0;
            width:100% !important;
        }
    </style>
</head>
<body style="margin:0;padding:0; font-family: Lato,Helvetica Neue,helvetica,sans-serif; color: #333; background: #f1f0ea">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td colspan="2" style="padding: 20px;">
                <table style="width: 600px; margin: 0 auto">
                    <tr>
                        <td style="padding: 20px">
                            <img src="http://localfoodnodes.org/email-assets/logo-dark.png" style="height:50px">
                        </td>
                    </tr>
                    <tr>
                        <td>@yield('content')</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr bgcolor="#333" style="color: #fff">
            <td style="padding: 20px;">
                <img src="http://localfoodnodes.org/email-assets/logo.png" style="height:50px"></td>
            </td>
            <td style="padding: 20px; text-align: right">
                Local Food Nodes<br>
                <a style="color: #fff; text-decoration: none;" href="mailto:info@localfoodnodes.org">info@localfoodnodes.org</a><br>
                +46(0) 735 325945<br>
                Backavägen 8 26868 Röstånga<br>
            </td>
        </tr>
    </table>
</body>
</html>
