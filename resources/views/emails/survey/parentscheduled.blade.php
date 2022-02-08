@component('mail::message')
# A parent participant have not reached the 10 days threshold of using Abiliti

Email: <code>{{ $email }}</code><br>
Country: <code>{{ $country }}</code><br>
Lacking days: <code>{{ $lacking }}</code><br>
Scheduled date for survey: <code>{{ $due_date }}</code><br>
Link: <code>{{ $link }}</code>

Kind regards,<br>
Split Second Research Limited<br>
@endcomponent