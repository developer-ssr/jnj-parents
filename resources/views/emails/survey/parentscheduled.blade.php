@component('mail::message')
# A parent participant hasn't reach the two weeks used of Abiliti

Email: <code>{{ $email }}</code><br>
Country: <code>{{ $country }}</code><br>
Lacking days: <code>{{ $lacking }}</code><br>
Scheduled date for survey: <code>{{ $due_date }}</code>

Kind regards,<br>
Split Second Research Limited<br>
@endcomponent