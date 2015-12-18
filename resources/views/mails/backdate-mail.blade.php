<table width="95%" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#0079BF" style="color: #ffffff; padding: 10px; font-size: 16px;" align="center">BACKDATE ENTRY</td>
  </tr>
  <tr>
    <td bgcolor="#F0F0F0" style="padding: 20px;" align="center">
      <p>You can now add a backdate entry for the date {{ $entry['backdate'] }}</p>
      <p><a href="{{url('time-tracker/backdate/' . $entry['otp'] . '/' . $entry['user_id'])}}">Click here</a></p>
    </td>
  </tr>
  @if ($comment != '')
  <tr>
    <td bgcolor="#F0F0F0" align="center">
      <p><strong>Reason</strong></p>
      <p>{!!$comment!!}</p>
    </td>
  </tr>
  @endif
</table>
