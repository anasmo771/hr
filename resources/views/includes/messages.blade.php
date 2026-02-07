@if (count($errors) > 0)
<div class = "alert alert-danger" id="message">
  <a type="button" onclick="hide()" style="float: left; font-size: 18px;"> x </a>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
@if(session()->has('success'))
<div class="alert alert-success" id="message">
  <a type="button" onclick="hide()" style="float: left; font-size: 18px;"> x </a>
  {{ session()->get('success') }}
</div>
@endif

@if(session()->has('error'))
<div class="alert alert-danger" id="message">
  <a type="button" onclick="hide()" style="float: left; font-size: 18px;"> x </a>
  {{ session()->get('error') }}
</div>
@endif

<script>
function hide(){
  document.getElementById("message").style.display = "none";
}
</script>