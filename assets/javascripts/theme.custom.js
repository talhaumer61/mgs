/* Add here all your JS customizations */
const sections      = document.getElementById("class_sections");
const subjects      = document.getElementById("class_subjects");
const chapters      = document.getElementById("subject_chapters");
const objective     = document.getElementById("objective-fields");
const chapterRows   = document.getElementById("chapters-rows");
const numOfLines    = document.getElementById("no_of_lines");
const papers        = document.getElementById("papers-list");
const printBtn      = document.getElementById("print-paper");
const marks         = document.getElementById("marks");
const no_of_subjective_parts = document.getElementById("no_of_subjective_parts");
const question_subjective_type = document.getElementById("question_subjective_type");
const question_block = document.getElementById("question-block");
const question_block_container = document.getElementById("question-block-container");
const parent_question_heading = document.getElementById("parent_question_heading");
const parent_question = document.getElementById("parent_question");
const generateBTn   = document.getElementById("generate_paper");
const numbersList = document.querySelectorAll(".numbers-container");
const submitResultBtn = document.querySelector("#submit-result");
const no_of_objective = document.getElementById("no_of_objective");
const no_of_subjective = document.getElementById("no_of_subjective");
const total_marks = document.getElementById("total_marks");
const has_sub = document.getElementById("has_sub");

axios.defaults.baseURL = BASEURL;

let globalMarkup = '';

no_of_subjective_parts?.addEventListener('change', function () {
  let totalBlocks = Number(no_of_subjective_parts.value);

  if (totalBlocks == 1){
    no_of_subjective_parts.value = 0;
    return new PNotify({
      title   : "Error"   ,
      text    : `Please select atleast 2 sub-questions`,
      type    : "error"    ,
      hide    : true  ,
      buttons: {
        closer  : true  ,
        sticker : false
      }
    });
  }


  let markup = question_block.outerHTML;

  globalMarkup = markup;

  for (let i = 0; i < totalBlocks; i ++){
    console.log(markup)
    // markup.getElementsByClassName('question')
    // console.log();
    if (i == 0){
      markup = markup.toString().replaceAll("Question", "Parent Question's");
      markup += question_block.outerHTML;
    }else {
      markup += question_block.outerHTML;
    }
  }

  question_block_container.innerHTML = null;
  question_block_container.insertAdjacentHTML("beforeend", markup);

  (function($) {
    'use strict';
    if ( $.isFunction( $.fn['summernote'] ) ) {
      $('.question').summernote();
      $('.question_urdu').summernote();
    }
  }).apply(this, [jQuery]);
  // question_block_container.getElementsByClassName('question').summernote()


  const lines = document.querySelectorAll("#no_of_lines").forEach((el, i) => {
    if (i == 0){
      el.value = 0;
      el.parentElement.style.display = "none";
      el.setAttribute("readonly", "readonly");
    }
  })

  const selects = document.querySelectorAll(".difficulty_level").forEach((el, i) => {
    if (i == 0){
      // el.value = "easy";
      el.parentElement.style.display='none';
      // el.lastElementChild.setAttribute("selected", "selected");
      el.setAttribute("readonly", "readonly");
      
    }
  })

  const checkboxes = document.querySelectorAll('.form-check-input').forEach((el, i) => {
    if (i != 0){
      el.parentElement.style.display="none"
      el.setAttribute('disabled', 'disabled');
    }
  })

});

printBtn?.addEventListener("click", function () {
  console.log("Working");
  let body = document.querySelector("body").innerHTML;
  let print = document.getElementById("paper-print-formate").innerHTML;
  document.querySelector("body").innerHTML = print;

  window.print();

  document.querySelector("body").innerHTML = body;
});

async function get_class_sections(value) {
  if (!value) {
    value = -1;
  }

  const res = await axios.get(`classes/class_sections/${value}`);

  const { data } = res;

  let markup = ``;

  sections.innerHTML = null;

  if (data.length > 0 && !data.code) {
    // Insert the data into select box
    data.forEach((section) => {
      markup += `<option value="${section.section_id}">${section.section_name}</option>`;
    });

    sections.insertAdjacentHTML("beforeend", markup);
  }
}

async function get_class_subjects(value) {
  if (!value) {
    value = -1;
  }

  const res = await axios.get(`classes/class_subjects/${value}`);

  const { data } = res;

  let markup = `<option value="-1">Select</option>`;

  subjects.innerHTML = null;

  if (data.length > 0 && !data.code) {
    // Insert the data into select box
    data.forEach((subject) => {
      markup += `<option value="${subject.subject_id}">${subject.subject_name}</option>`;
    });

    subjects.insertAdjacentHTML("beforeend", markup);
  }
}

async function get_subject_chapters(value) {
  if (!value) {
    value = -1;
  }

  const res = await axios.get(`subjects/subject_chapters/${value}`);

  const { data } = res;

  let markup = `<option value="-1">Select</option>`;

  chapters.innerHTML = null;

  if (data.length > 0 && !data.code) {
    // Insert the data into select box
    data.forEach((chapter) => {
      markup += `<option value="${chapter.chapter_id}">${chapter.chapter_name}</option>`;
    });

    console.log(data);

    chapters.insertAdjacentHTML("beforeend", markup);
  }
}

async function get_subject_chapters_list(value) {
  if (!value) {
    value = -1;
  }

  const { data } = await axios.get(`subjects/subject_chapters/${value}`);

  let markup = ``;

  chapterRows.innerHTML = null;

  if (data.length > 0 && !data.code) {
    // Insert the data into select box
    data.forEach((chapter1) => {
      markup += `<div><div style="margin-bottom: 20px; " class="col-md-6"><label class="control-label">Chapter <span class="required">*</span></label><select class="form-control" name="query[chapter_id][]" id="subject_chapters" required title="Must Be Required" data-plugin-selecttwo data-width="100%" ><option value="">Select Subject First</option>`;

      data.forEach((chapter) => {
        markup += `<option ${
          chapter1.chapter_id == chapter.chapter_id ? "selected" : ""
        } value="${chapter.chapter_id}">${chapter.chapter_name}</option>`;
      });

      markup += `</select></div> 
              <div style="margin-bottom: 20px;" class="col-md-3">
                <label class="control-label">Objective Questions <span class="required">*</span></label>
                <input type="number" class="form-control objective_question_marks" name="query[objective_questions][]" id="section_name"  required="" title="Must Be Required" aria-required="true">
              </div>
              
              <div style="margin-bottom: 20px;" class="col-md-3">
                <label class="control-label">Subjective Questions <span class="required">*</span></label>
                <input type="number" class="form-control subjective_question_marks" name="query[subjective_questions][]" id="section_name" required="" title="Must Be Required" aria-required="true">
              </div>
            </div>`;
    });

    chapterRows.insertAdjacentHTML("beforeend", markup);
  }
}

async function get_max_marks(el){
  // Get the subject Id
  const subject_id = document.querySelector("[name='subject_id']").value;

  // Get paperType Id
  const examTypeId = document.querySelector("[name='paper_type']").value;

  const { data } = await axios.get(`/papers/get_max_marks/${subject_id}/${examTypeId}`)

  document.getElementById("marks_of_objective").innerText = (data.objective_marks);
  document.getElementById("marks_of_subjective").innerText = (data.subjective_marks);
  document.getElementById("total_marks").innerText = (data.subjective_marks  + data.objective_marks);

}

function show_options_tab(value) {
  
  for (var key in QuestionTypeArray) {
    if($(`#question_subjective_type option[value='${key}'`).length == 0){
      $('#question_subjective_type').append($('<option>', {
          value: key,
          text:  QuestionTypeArray[key]
      }));
    } else{
      $(`#yourSelect option[value='${key}'`).remove();
    }
  }
  
  if (value == "subjective") {
    marks.value = SUBJECTIVE_MARKS;
    question_subjective_type.parentElement.style.display = "block"
    if (question_subjective_type.value == "1"){

      no_of_subjective_parts.removeAttribute("disabled" );
    }

    no_of_subjective_parts.parentElement.style.display = "block";
    has_sub.parentElement.style.display = "block";

    //Remove marks disable in case of subjective
    marks.removeAttribute("readonly");

    question_subjective_type.removeAttribute("disabled")
    question_subjective_type.style.display = "block";
    $("#question_subjective_type option[value='3']").remove();
    $("#question_subjective_type option[value='4']").remove();
    $("#question_subjective_type option[value='5']").remove();
    objective.style.display = "none";

    document.querySelectorAll(".difficulty_level").forEach(el => {
      el.removeAttribute("disabled");
      // el.select2("destroy");
      // $('.difficulty_level').select2("destroy");
      el.parentElement.style.display = "block";
    });

    numOfLines.removeAttribute("disabled");
    numOfLines.parentElement.style.display = "block";

    document
      .querySelectorAll("#objective-fields input")
      .forEach((el) => el.setAttribute("disabled", "disabled"));
    document
      .querySelectorAll("#objective-fields select")
      .forEach((el) => el.setAttribute("disabled", "disabled"));
    return;
  }

  if (value == "objective") {
    // Disable the Subjective Question Type Select Box
    // question_subjective_type.setAttribute("disabled", "disabled");
    // question_subjective_type.parentElement.style.display = "none";
    
    question_subjective_type.removeAttribute("disabled");
    
    $("#question_subjective_type option[value='1']").remove();
    $("#question_subjective_type option[value='2']").remove();
    has_sub.parentElement.style.display = "none";

    // Disable the No. of Sub-questions Option
    no_of_subjective_parts.setAttribute("disabled", "disabled");
    no_of_subjective_parts.parentElement.style.display = "none";

    // Set the Marks of Objective Question
    marks.value = OBJECTIVE_MARKS;

    // Show the Objective fields Options
    objective.style.display = "block";

    // Disable Difficulty Level and hide it as well
    document.querySelectorAll(".difficulty_level").forEach( el => {
      el.setAttribute("disabled", "disabled");
      // el.select2("destroy");
      // $('.difficulty_level').select2("destroy");

      el.parentElement.style.display = "none";
      // el.select2();
    });

    // Diable the number of lines and also hide it
    numOfLines.setAttribute("disabled", "disabled");
    
    //Disabled marks in case of objective only
    marks.setAttribute("readonly", "readonly");
    
    numOfLines.parentElement.style.display = "none";
    console.log("working");

    // Enable the options fileds and select box for the objective questions.
    document
      .querySelectorAll("#objective-fields input")
      .forEach((el) => el.removeAttribute("disabled"));
    document
      .querySelectorAll("#objective-fields select")
      .forEach((el) => el.removeAttribute("disabled"));

    let markup = question_block.outerHTML;
    totalBlocks = 0
    globalMarkup = markup;

    for (let i = 0; i < totalBlocks; i ++){
      console.log(markup)
      // markup.getElementsByClassName('question')
      // console.log();
      if (i == 0){
        markup = markup.toString().replaceAll("Question", "Parent Question's");
        markup += question_block.outerHTML;
      }else {
        markup += question_block.outerHTML;
      }

    }

    question_block_container.innerHTML = null;
    question_block_container.insertAdjacentHTML("beforeend", markup);
    (function($) {
      'use strict';
      if ( $.isFunction( $.fn['summernote'] ) ) {
        $('.question').summernote();
        $('.question_urdu').summernote();
      }
    }).apply(this, [jQuery]);

    const lines = document.querySelectorAll("#no_of_lines").forEach((el, i) => {
      if (i == 0){
        el.value = 0;
        el.parentElement.style.display = "none";
        el.setAttribute("readonly", "readonly");
      }
    })

    const selects = document.querySelectorAll(".difficulty_level").forEach((el, i) => {
      if (i == 0){
        // el.value = "easy";
        el.parentElement.style.display='none';
        // el.lastElementChild.setAttribute("selected", "selected");
        el.setAttribute("readonly", "readonly");
        
      }
    })

    const checkboxes = document.querySelectorAll('.form-check-input').forEach((el, i) => {
      if (i != 0){
        el.parentElement.style.display="none"
        el.setAttribute('disabled', 'disabled');
      }
    })
  }
}

function show_has_sub(el){
  
  if (el.value == "1"){
    has_sub.removeAttribute("disabled");
    
  }else{
    has_sub.setAttribute("disabled", "disabled")

  }

}
function show_no_of_parts(el){
  if (el.value == "2"){
    no_of_subjective_parts.setAttribute("disabled", "disabled")
    no_of_subjective_parts.value = 0;
    let markup = question_block.outerHTML;
    totalBlocks = 0
    globalMarkup = markup;

    for (let i = 0; i < totalBlocks; i ++){
      console.log(markup)
      // markup.getElementsByClassName('question');
      // console.log()
      if (i == 0){
        markup = markup.toString().replaceAll("Question", "Parent Question's");
        markup += question_block.outerHTML;
      }else {
        markup += question_block.outerHTML;
      }

    }

    question_block_container.innerHTML = null;
    question_block_container.insertAdjacentHTML("beforeend", markup);
    (function($) {
      'use strict';
      if ( $.isFunction( $.fn['summernote'] ) ) {
        $('.question').summernote();
        $('.question_urdu').summernote();
      }
    }).apply(this, [jQuery]);


    const lines = document.querySelectorAll("#no_of_lines").forEach((el, i) => {
      if (i == 0){
        el.value = 0;
        el.parentElement.style.display = "none";
        el.setAttribute("readonly", "readonly");
      }
    })

    const selects = document.querySelectorAll(".difficulty_level").forEach((el, i) => {
      if (i == 0){
        // el.value = "easy";
        el.parentElement.style.display='none';
        // el.lastElementChild.setAttribute("selected", "selected");
        el.setAttribute("readonly", "readonly");
        
      }
    })

    const checkboxes = document.querySelectorAll('.form-check-input').forEach((el, i) => {
      if (i != 0){
        el.parentElement.style.display="none"
        el.setAttribute('disabled', 'disabled');
      }
    })
    
  }

  if (el.value == "1"){
    no_of_subjective_parts.removeAttribute("disabled");
  }


}

async function getClassPapers(el) {
  if (!el.value) {
    el.value = -1;
  }

  const { data } = await axios.get(`papers/class/${el.value}`);

  let markup = `<option value="-1">Select</option>`;

  papers.innerHTML = null;

  if (data.length > 0 && !data.code) {
    // Insert the data into select box
    data.forEach((paper) => {
      markup += `<option value="${paper.paper_id}">${paper.paper_id}</option>`;
    });

    console.log(data);

    papers.insertAdjacentHTML("beforeend", markup);
  }
}

const generatForm = document.getElementById("paper-generate-form");

generatForm?.addEventListener("submit", function (e){

  // Converting string into number
  const objectiveMarks = document.getElementById("marks_of_objective").textContent * 1;

  // Converting string into number by multiplying it with 1
  const subjectiveMarks = document.getElementById("marks_of_subjective").textContent * 1;

  e.preventDefault();

  let subjectiveSum = 0;
  let objectiveSum = 0;

  document.querySelectorAll(".objective_question_marks").forEach((el, i) => {
    objectiveSum += Number(el.value) * 1;
  })

  if (objectiveSum * 1 != objectiveMarks || objectiveSum * 1 > objectiveMarks || objectiveSum * 1 < objectiveMarks  ){
    return new PNotify({
      title   : "Error"   ,
      text    : `Please select number of objective questions worth ${document.getElementById("marks_of_objective").textContent} marks. Each question is worth ${OBJECTIVE_MARKS} marks`,
      type    : "error"    ,
      hide    : true  ,
      buttons: {
        closer  : true  ,
        sticker : false
      }
    });
  }

  document.querySelectorAll(".subjective_question_marks").forEach((el, i) => {
    subjectiveSum += Number(el.value) * 1;
  })

  if (subjectiveSum * 5 != subjectiveMarks || subjectiveSum * 5 > subjectiveMarks || subjectiveSum * 5 < subjectiveMarks ){
    console.log({subjectiveSum})
    console.log({subjectiveMarks})
    return new PNotify({
      title   : "Error"   ,
      text    : `Please select number of subjective questions worth ${document.getElementById("marks_of_subjective").textContent} marks. Each question is worth ${SUBJECTIVE_MARKS} marks`,
      type    : "error"    ,
      hide    : true  ,
      buttons: {
        closer  : true  ,
        sticker : false
      }
    });
  }

  if (subjectiveMarks == 0 || objectiveMarks == 0){
    return new PNotify({
      title   : "Error"   ,
      text    : `Please Select all the field before generating paper`,
      type    : "error"    ,
      hide    : true  ,
      buttons: {
        closer  : true  ,
        sticker : false
      }
    });
  }

  generatForm.submit();
});

function checkNumbers () {
  let isEverythingOk = true;
  numbersList.forEach(el => {
    const maxNumbers = Number(el.getAttribute("data-max-numbers"));
    const inputs = el.getElementsByClassName("form-control");
    let sum = 0;
    for (var i=0; i < inputs.length; i++) {
      sum += inputs[i].value * 1;
      console.log({sum, maxNumbers});
    }

    if (sum > maxNumbers){
      el.classList.add("bg-danger");
      isEverythingOk = false;
    }else{
      el.classList.remove("bg-danger");
    }

  })
  return isEverythingOk;
}

document.getElementById("result-form")?.addEventListener("submit", function (e) {
  e.preventDefault();

  let isVerified = checkNumbers();
  console.log(isVerified);
  if (isVerified){
    document.getElementById("result-form").submit();
  }else{
    alert("Please provide valid marks for each student");
  }

})

no_of_subjective?.addEventListener("keyup", calcTotalMarks);
no_of_objective?.addEventListener("keyup", calcTotalMarks);


function calcTotalMarks() {

  let subjectiveMarks =  no_of_subjective.value * SUBJECTIVE_MARKS ?? 0 * SUBJECTIVE_MARKS;
  let objectiveMarks = no_of_objective.value * OBJECTIVE_MARKS ?? 0 * OBJECTIVE_MARKS;
  total_marks.value = subjectiveMarks + objectiveMarks;
}

// (function($) {
// 	'use strict';
// 	if ( $.isFunction( $.fn['select2'] ) ) {
//     $('.difficulty_level').select2();
// 	}
// }).apply(this, [jQuery]);