<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<div class="wrapper">


    <table width="630" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr>
            <td width = "368" colspan = "2" align = "left" valign = "bottom" style = "font-family:Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold; color:#000; padding:0 30px 10px 0;">
                Email Verification on TruckCode Website
            </td>
        </tr>
        <tr>
            <td colspan = "4" align = "left" valign = "top"><table width = "100%" border = "0" cellspacing = "2" cellpadding = "2">
                    <tr>
                        <td>
                            <table width = "100%" border = "0" cellpadding = "6" cellspacing = "0">
                                <tr>
                                    <td align = "left">Email</td>
                                    <td align = "left">: {{$email}}</td>
                                </tr>
                                <tr>
                                    <td align = "left">Confirm Code</td>
                                    <td align = "left">: {{$confirm_code}}</td>
                                </tr>
                                <tr>
                                    <td align = "left">Confirm DateTime</td>
                                    <td align = "left">: {{date('Y-m-d H:i:s')}}</td>
                                </tr>
                                <tr>
                                    <td colspan = "2" align = "left">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan = "2" align = "left"><strong>Best Regards, &nbsp;
                                        </strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table></td>
        </tr>
    </table>


</div>

</body>
</html>
