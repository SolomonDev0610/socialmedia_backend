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
                New email arrived from ContactUs form in TruckCode Website
            </td>
        </tr>
        <tr>
            <td colspan = "4" align = "left" valign = "top"><table width = "100%" border = "0" cellspacing = "2" cellpadding = "2">
                    <tr>
                        <td>
                            <table width = "100%" border = "0" cellpadding = "6" cellspacing = "0">
                                <tr>
                                    <td align = "left">Customer Email</td>
                                    <td align = "left">: {{$sender_email}}</td>
                                </tr>
                                <tr>
                                    <td align = "left">Customer FullName</td>
                                    <td align = "left">: {{$fullname}}</td>
                                </tr>
                                <tr>
                                    <td align = "left">Subject</td>
                                    <td align = "left">: {{$subject}}</td>
                                </tr>
                                <tr>
                                    <td align = "left">Message</td>
                                    <td align = "left">: {{$sender_message}}</td>
                                </tr>
                                <tr>
                                    <td align = "left">Arrived DateTime</td>
                                    <td align = "left">: {{date('Y-m-d H:i:s')}}</td>
                                </tr>
                                <tr>
                                    <td colspan = "2" align = "left">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan = "2" align = "left"><strong>Best Regards</strong></td>
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
