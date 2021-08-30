<hr>

<form action="{{url('employee-details')}}/{{$education->emp_id}}/education" method="post" onsubmit="return false" id="education_form">
{{csrf_field()}}
  <div class="form-group">
    <label for="level_of_education">Level Of Education:</label>
    <input type="text" class="form-control" name="level_of_education" id="level_of_education">
  </div>
  <div class="form-group">
    <label for="exam_title">Exam/Degree title:</label>
    <input type="text" class="form-control" name="exam_title" id="exam_title">
  </div>
  <div class="form-group">
    <label for="group">Concentration/ Major/Group:</label>
    <input type="text" class="form-control" name="group" id="group">
  </div>
  <div class="form-group">
    <label for="institute">Institute Name:</label>
    <input type="text" class="form-control" name="institute" id="institute">
  </div>
  <div class="form-group">
    <label for="result">Result:</label>
    <input type="text" class="form-control" name="result" id="result">
  </div>
  <div class="form-group">
    <label for="cgpa">CGPA :</label>
    <input type="text" class="form-control" name="cgpa" id="cgpa">
  </div>
  <div class="form-group">
    <label for="scale">Scale :</label>
    <input type="text" class="form-control" name="scale" id="scale">
  </div>
  <div class="form-group">
    <label for="year">Year of Passing:</label>
    <input type="text" class="form-control" name="year" id="year">
  </div>
  <div class="form-group">
    <label for="duration">Duration (Years):</label>
    <input type="text" class="form-control" name="duration" id="duration">
  </div>
</form>


<hr>