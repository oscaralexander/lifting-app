@use('App\Enums\SubmissionPdfType')
@use('App\Lib\FormItems')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $submission->form->name }} @lang('submission_pdf.for') {{ $submission->stockItem->machine->name }} ({{ $submission->stockItem->stock_id }})</title>
        <style type="text/css">
            :root {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            body,
            html {
                font-family: sans-serif;
                font-size: 14px;
                margin: 0;
                padding: 0;
            }

            body {
                margin: 1cm;
            }

            a {
                color: #0056A4;
                text-decoration: underline;
            }

            h1 {
                margin: 0 0 .5cm 0;
            }

            img {
                display: block;
                max-height: 10cm;
                max-width: 15cm;
            }

            .header__logo {
                height: 1.5cm;
                margin-bottom: 1cm;
                width: auto;
            }

            .header__qrCol {
                width: 3cm;
            }

            .header__qr {
                height: 2.5cm;
                float: right;
                width: 2.5cm;
            }

            .header {
                margin: 1cm 0;
            }

            .header__details {
                border-spacing: 0;
                border-collapse: collapse;
                width: 100%;
            }
            
            .header__details th,
            .header__details td {
                line-height: 1.5;
                text-align: left;
            }

            .header__details th {
                font-weight: bold;
            }

            .header__signature {
                max-width: 75%;
                margin: .25cm 0;
                width: 7.5cm;
            }

            .submission {
                border-bottom: 1px solid #d6dfec;
            }

            .submission__field {
                border-top: 1px solid #d6dfec;
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .submission__field {
                line-height: 1.5;
                padding: .25cm 0;
            }

            .submission__question {
                font-size: 14px;
                font-weight: bold;
            }

            .submission__answer {
                font-size: 16px;
            }

            .submission__fieldGroup {
                border-top: 1px solid #d6dfec;
                break-inside: avoid;
                margin: 0;
                padding: .25cm 0;
                page-break-inside: avoid;
            }

            .submission__fieldGroupName {
                font-weight: bold;
            }

            .submission__fieldGroupFields {
                border: 1px solid #d6dfec;
                border-top: none;
                margin: .25cm 0;
            }

            .submission__formComment {
                background: #f6f6f6;
                border-top: 1px solid #d6dfec;
                line-height: 1.5;
                padding: .25cm;
            }

            .submission__fieldGroupFields .submission__field {
                padding: .25cm;
            }

            .light {
                color: #999;
            }
        </style>
    </head>
    <body>
        <header class="header">
            <img alt="Van der Spek" class="header__logo" src="{{ public_path() . '/assets/img/van-der-spek.svg' }}" />
            <table class="header__details">
                <tr>
                    <td>
                        <h1>{{ $submission->form->name }} <span style="font-weight: 400;">@lang('submission_pdf.for')</span> {{ $submission->stockItem->machine->name }} ({{ $submission->stockItem->stock_id }})</h1>
                        <table class="header__details">
                            <thead>
                                <tr>
                                    <th scope="col" valign="top" width="50%">@lang('submission_pdf.author')</th>
                                    <th scope="col" valign="top" width="50%">@lang('submission_pdf.signed_by')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>        
                                    <td valign="top" width="50%">
                                        {{ $submission->user->name }}<br/>
                                        <a href="mailto:{{ $submission->user->email }}">{{ $submission->user->email }}</a><br/>
                                        <span class="light">{{ $submission->created_at->isoFormat('D MMM Y (HH:mm)') }}</span>
                                    </td>
                                    <td align="top" width="50%">
                                        @if ($submission->hasSignature())
                                            {{ $submission->signature_name }}<br/>
                                            <span class="light">{{ $submission->signed_at->isoFormat('D MMM Y (HH:mm)') }}</span><br/>
                                            <img alt="Signature" src="{{ $signaturePath }}" class="header__signature" />
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="header__qrCol">
                        <img alt="QR code" class="header__qr" src="{{ $qrPath }}" />
                    </td>
                </tr>
            </table>
        </header>
        @php
            if ($type === SubmissionPdfType::EXTERNAL) {
                $items = FormItems::getPublic($submission->form);
            } else {
                $items = FormItems::get($submission->form);
            }
        @endphp
        <div class="submission">
            @foreach ($items as $item)
                @switch ($item['type'])
                    @case('field')
                        <x-submission.formatted-answer-pdf
                            :answer="$submission->getAnswerForField($item['field']->pivot->id)"
                            :field="$item['field']"
                        />
                        @break
                    @case('fieldGroup')
                        <x-submission.field-group-pdf
                            :field-group="$item['fieldGroup']"
                            :submission="$submission"
                        />
                        @break
                    @case('formComment')
                        <x-submission.form-comment :form-comment="$item['formComment']" />
                        @break
                @endswitch
            @endforeach
        </div>
    </body>
</html>