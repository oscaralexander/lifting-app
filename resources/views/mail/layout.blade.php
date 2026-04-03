<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Van der Spek Service Portal</title>
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet">
        <style type="text/css">
            span.MsoHyperlink {
                color: inherit;
            }

            span.MsoHyperlinkFollowed {
                color: inherit;
                mso-style-priority: 99;
            }

            #outlook a {
                padding: 0;
            } /* Force Outlook to provide a "view in browser" message */

            .ReadMsgBody {
                width: 100%;
            }

            .ExternalClass {
                width: 100%;
            } /* Force Hotmail to display emails at full width */

            .ExternalClass,
            .ExternalClass div,
            .ExternalClass font,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass td {
                line-height: 100%;
            } /* Force Hotmail to display normal line spacing */

            a,
            blockquote,
            body,
            li,
            p,
            table,
            td {
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            } /* Prevent WebKit and Windows mobile changing default text sizes */

            table,
            td {
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
            } /* Remove spacing between tables in Outlook 2007 and up */

            body {
                margin: 0;
                padding: 0;
            }

            img {
                border: 0;
                height: auto;
                line-height: 100%;
                outline: none;
                text-decoration: none;
            }

            table {
                border-collapse: collapse !important;
            }

            body{
                margin: 0;
                min-height: 100% !important;
                padding: 0;
                width: 100% !important;
            }

            body {
                background-color: #F3F7FE;
                color: #4F5661;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 16px;
            }

            h1, h2, h3, h4 {
                color: #000000;
                display: block;
                font-family: 'Inter', system-ui, sans-serif;
                font-weight: bold;
                line-height: 1.25;
                margin: 0;
            }

            h1 {
                color: #0056A4;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 20px;
                text-align: center;
            }

            p {
                color: #4F5661;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 16px;
                line-height: 1.5;
                margin: 1.5em 0;
            }

            .canvas {
                background-color: #F3F7FE;
                padding: 8px;
            }

            .logo {
                margin: 20px auto;
                width: 128px;
            }

            .page {
                background-color: #FFFFFF;
                padding: 40px;
            }

            .title {
                margin-bottom: 40px;
            }

            .text {
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 16px;
                line-height: 1.5;
                text-align: left;
            }

            .text-light {
                color: #9097A3;
            }

            a {
                color: #0056A4;
                text-decoration: underline;
            }

            .btn {
                background-color: #0056A4;
                border-radius: 8px;
                color: #FFFFFF;
                display: inline-block;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 16px;
                font-weight: 400;
                mso-padding-alt: 0;
                padding: 14px 20px;
                text-decoration: none;
            }

            @media only screen and (max-width: 480px) {
                body, table, td, p, a, li, blockquote {
                    -webkit-text-size-adjust: none !important;
                } /* Prevent Webkit platforms from changing default text sizes */

                body{
                    min-width:100% !important;
                    width: 100% !important;
                } /* Prevent iOS Mail from adding padding to the body */

                .page {
                    padding: 40px 20px !important;
                }
            }
        </style>
    </head>
    <body itemscope itemtype="http://schema.org/EmailMessage">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" class="canvas">
                    <a href="{{ config('app.url') }}" target="_blank"><img alt="Van der Spek" class="logo" src="{{ asset('assets/img/van-der-spek.png') }}"></a>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-radius: 12px; margin: auto; max-width: 600px;">
                        <tr>
                            <td class="page" style="border-radius: 12px; padding: 40px;">
                                @yield('content')
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>