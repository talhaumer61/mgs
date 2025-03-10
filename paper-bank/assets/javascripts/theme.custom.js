/* Add here all your JS customizations */
const sections      = document.getElementById("class_sections");
const subjects      = document.getElementById("class_subjects");
const chapters      = document.getElementById("subject_chapters");
const chapters_to   = document.getElementById("subject_chapters_to");
const objective     = document.getElementById("objective-fields");
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
const topicSelect = document.getElementById('chapter_topics');

const save_and_next = document.getElementById('save_and_next');

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
      markup += `<option value="${chapter.chapter_id}">Chapter#${chapter.chapter_no} (${chapter.chapter_name})</option>`;
    });

    console.log(data);

    chapters.insertAdjacentHTML("beforeend", markup);
  }
}

async function get_subject_chapters_fltr(value) {
  if (!value) {
    value = -1;
  }

  const res = await axios.get(`subjects/subject_chapters/${value}`);

  const { data } = res;

  let markup = `<option value="-1">Select</option>`;

  chapters.innerHTML = null;
  chapters_to.innerHTML = null;

  if (data.length > 0 && !data.code) {
    // Insert the data into select box
    data.forEach((chapter) => {
      markup += `<option value="${chapter.chapter_id}">Chapter#${chapter.chapter_no} (${chapter.chapter_name})</option>`;
    });

    console.log(data);

    chapters.insertAdjacentHTML("beforeend", markup);
    chapters_to.insertAdjacentHTML("beforeend", markup);
  }
}


async function get_chapter_topics({value}){
  if (!value) {
    value = -1;
  }

  const { data } = await axios.get(`chapters/topics/${value}`);

  console.log(data);

  let markup = `<option value=""> Select</option>`;

  topicSelect.innerHTML = null;

  if (data.length > 0 && !data.code) {
    console.log(data)
    // Insert the data into select box
    data.forEach((topic) => {
      markup += `<option value="${topic.topic_id}">${topic.topic_name}</option>`;
    });

    topicSelect.insertAdjacentHTML("beforeend", markup);
  }
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

// New Code Addition
// Date 08-04-2021
const id = document.querySelector("[name='id_question_type']").value;

let submitFileds;
let clearFileds;

if (id == '1' || id == '2') {

  submitFileds = {
    question_id : document.querySelector("[name='question_id']"),
    id_question_type : document.querySelector("[name='id_question_type']"),
    id_board : document.querySelector("[name='id_board']"),
    id_publisher : document.querySelector("[name='id_publisher']"),
    id_subject : document.querySelector("[name='id_subject']"),
    id_class : document.querySelector("[name='id_class']"),
    id_topic : document.querySelector("[name='id_topic']"),
    id_chapter : document.querySelector("[name='id_chapter']"),
    page_num : document.querySelector("[name='page_num']"),
    question_english : document.querySelector("[name='question_english']"),
    question_urdu : document.querySelector("[name='question_urdu']")
  }
  clearFileds = {
    page_num : submitFileds.page_num,
    question_english : submitFileds.question_english,
    question_urdu : submitFileds.question_urdu
  };
}
else{
  submitFileds = {
      question_id : document.querySelector("[name='question_id']"),
      id_question_type : document.querySelector("[name='id_question_type']"),
      id_board : document.querySelector("[name='id_board']"),
      id_publisher : document.querySelector("[name='id_publisher']"),
      id_subject : document.querySelector("[name='id_subject']"),
      id_class : document.querySelector("[name='id_class']"),
      id_topic : document.querySelector("[name='id_topic']"),
      id_chapter : document.querySelector("[name='id_chapter']"),
      page_num : document.querySelector("[name='page_num']"),
      question_english : document.querySelector("[name='question_english']"),
      question_urdu : document.querySelector("[name='question_urdu']"),
      e_option_a : document.querySelector("[name='e_option_a']"),
      e_option_b : document.querySelector("[name='e_option_b']"),
      e_option_c : document.querySelector("[name='e_option_c']"),
      e_option_d : document.querySelector("[name='e_option_d']"),
      e_option_correct : document.querySelector("[name='e_option_correct']"),
      u_option_a : document.querySelector("[name='u_option_a']"),
      u_option_b : document.querySelector("[name='u_option_b']"),
      u_option_c : document.querySelector("[name='u_option_c']"),
      u_option_d : document.querySelector("[name='u_option_d']"),
      u_option_correct : document.querySelector("[name='u_option_correct']")
    }
    clearFileds = {
      page_num : submitFileds.page_num,
      question_english : submitFileds.question_english,
      question_urdu : submitFileds.question_urdu,
      e_option_a : submitFileds.e_option_a,
      e_option_b : submitFileds.e_option_b,
      e_option_c : submitFileds.e_option_c,
      e_option_d : submitFileds.e_option_d,
      e_option_correct : submitFileds.e_option_correct,
      u_option_a : submitFileds.u_option_a,
      u_option_b : submitFileds.u_option_b,
      u_option_c : submitFileds.u_option_c,
      u_option_d : submitFileds.u_option_d,
      u_option_correct : submitFileds.u_option_correct,
    
    };
}

let current_serial_number = 1;

const react = {
  data : [],
  questionId : null,
  id: document.querySelector("[name='id_question_type']").value,

  get getQuestionid(){
    return this.questionId;
  },

  get getData(){
    return this.data;
  },
  set setQuestionid(qid){
    this.questionId = qid;
    // this.mount();
  },

  set setData(value) {
    this.data = value;
    this.mount();
  },
  

  // Generate Row
  generatRow(){
    let markup = '';
    if (this.id == '1' || this.id == '2'){
      
      this.data.forEach((el, i) => {
        
         markup += `<tr class="question_id_${el.question.question_id}">
           <td>${i+1}</td>
           <td>${el.question.page_num}</td>
           <td>
             <span class="pull-left">${el.question.question_english}</span><br><br>
             <span class="pull-right" dir="rtl">${el.question.question_urdu}</span>
           </td>
           <td>
             <a class="modal-with-move-anim btn btn-success btn-xs ml-xs" data-id="${el.question.question_id}" onclick="react.updateRow(${el.question.question_id})" ><i class=" fa fa-edit"></i></a>
             <button class="remove-btn btn btn-xs btn-danger" onclick="react.deleteRow(${el.question.question_id})"  ><i class=" fa fa-trash"></i></button>
           </td>
         </tr>`;
     }); 
    }
    else{

      this.data.forEach((el, i) => {
        markup += `<tr class="question_id_${el.question.question_id}">
        <td>${i+1}</td>
        <td>
        <span class="pull-left">${el.question.question_english}</span><br><br>
        <span class="pull-right" dir="rtl">${el.question.question_urdu}</span>
        </td>
        <td>
          <p>${el.obj_answer.e_option_a}</p>
          <p>${el.obj_answer.u_option_a}</p>
        </td>
        <td>
          <p>${el.obj_answer.e_option_b}</p>
          <p>${el.obj_answer.u_option_b}</p>
        </td>
        <td>
          <p>${el.obj_answer.e_option_c}</p>
          <p>${el.obj_answer.u_option_c}</p>
        </td>
        <td>
          <p>${el.obj_answer.e_option_d}</p>
          <p>${el.obj_answer.u_option_d}</p>
        </td>
        <td>
          <p>${el.obj_answer.e_option_correct}</p>
          <p>${el.obj_answer.u_option_correct}</p>
        </td>
        <td>
          <a class="modal-with-move-anim btn btn-success btn-xs ml-xs" data-id="${el.question.question_id}" onclick="react.updateRow(${el.question.question_id})" ><i class=" fa fa-edit"></i></a>
          <button class="remove-btn btn btn-xs btn-danger" onclick="react.deleteRow(${el.question.question_id})"  ><i class=" fa fa-trash"></i></button>
        </td>
      </tr>`;
     }); 
    }
    return markup;
  },

  // Update the Row.
  async updateRow(id){
    
    const res = await axios({
      method: 'get',
      url : `questions/get_question/${id}`
    })

    const { data } = res;
    
    if (data['status'] !== true){
      new PNotify({
        title : "Error Processing your request",
        text    : "Invalid Record Id provided",
        type    : "error",
        hide    : true,
        buttons: {
          closer  : true  ,
          sticker : false
        }
      })
    }

    // Change the fields
    for (const key in submitFileds) {
      submitFileds[key].value = data['question'][key];
    }


    if (this.id == '1' || this.id == '2'){
        // Insert the html in the text boxes
      const [question_english, question_urdu] = document.querySelectorAll('.note-editable');
      question_english.innerHTML = data['question']['question_english'];
      question_urdu.innerHTML = data['question']['question_urdu'];
      document.getElementById("page_num").value = data['question']['page_num'];
    }
    else{
        console.log(data['obj_answer'])
      // Insert the html in the text boxes
      const [questeion_english, question_urdu] = document.querySelectorAll('.note-editable');

      questeion_english.innerHTML = data['question']['question_english'];
      question_urdu.innerHTML = data['question']['question_urdu'];
      document.getElementById("page_num").value = data['question']['page_num'];
      document.getElementById("e_option_a").value = data['obj_answer']['e_option_a'];
      document.getElementById("e_option_b").value = data['obj_answer']['e_option_b'];
      document.getElementById("e_option_c").value = data['obj_answer']['e_option_c'];
      document.getElementById("e_option_d").value = data['obj_answer']['e_option_d'];
      document.getElementById("e_option_correct").value = data['obj_answer']['e_option_correct'];
      document.getElementById("u_option_a").value = data['obj_answer']['u_option_a'];
      document.getElementById("u_option_b").value = data['obj_answer']['u_option_b'];
      document.getElementById("u_option_c").value = data['obj_answer']['u_option_c'];
      document.getElementById("u_option_d").value = data['obj_answer']['u_option_d'];
      document.getElementById("u_option_correct").value = data['obj_answer']['u_option_correct'];

    }

    
    
  },

  // Delete the data
  async deleteRow(id){
    const res = await axios({
      method: 'get',
      url: `questions/delete_react/${id}`
    });

    if (res.data === true){
      const data = this.getData.filter(el => el.question.question_id != id);
      this.setData = data;
      
    }else{
      new PNotify({
        title   : "Error Deleting record"   ,
        text    : "Something went wrong while deleting your record"    ,
        type    : "error"    ,
        hide    : true  ,
        buttons: {
          closer  : true  ,
          sticker : false
        }
      });
    }
  },

  async saveData(){
    let data1;
    if (this.id == '1' || this.id == '2') {
      data1 = {
        question_id : submitFileds.question_id.value ?? 0,
        id_question_type: submitFileds.id_question_type.value,
        id_board : submitFileds.id_board.value,
        id_publisher : submitFileds.id_publisher.value,
        id_class : submitFileds.id_class.value,
        id_topic : submitFileds.id_topic.value,
        id_subject : submitFileds.id_subject.value,
        id_chapter : submitFileds.id_chapter.value,
        question_english : submitFileds.question_english.value,
        question_urdu : submitFileds.question_urdu.value,
        page_num : submitFileds.page_num.value,
      }
    }
    else if(this.id == '3'){
      
      
      data1 = {
        question_id : submitFileds.question_id.value ?? 0,
        id_question_type: submitFileds.id_question_type.value,
        id_board : submitFileds.id_board.value,
        id_publisher : submitFileds.id_publisher.value,
        id_class : submitFileds.id_class.value,
        id_topic : submitFileds.id_topic.value,
        id_subject : submitFileds.id_subject.value,
        id_chapter : submitFileds.id_chapter.value,
        question_english : submitFileds.question_english.value,
        question_urdu : submitFileds.question_urdu.value,
        page_num : submitFileds.page_num.value,
        e_option_a : submitFileds.e_option_a.value,
        e_option_b : submitFileds.e_option_b.value,
        e_option_c : submitFileds.e_option_c.value,
        e_option_d : submitFileds.e_option_d.value,
        e_option_correct : submitFileds.e_option_correct.value,
        u_option_a : submitFileds.u_option_a.value,
        u_option_b : submitFileds.u_option_b.value,
        u_option_c : submitFileds.u_option_c.value,
        u_option_d : submitFileds.u_option_d.value,
        u_option_correct : submitFileds.u_option_correct.value,
      }
    }
    const {data} =  await axios({
      method : 'post',
      url : 'questions/save_question',
      data : data1
    });
    
    console.log(submitFileds.question_id.value);

    if (submitFileds.question_id.value == 0){
      this.setData = [...react.getData, data];
      this.setQuestionid = data.question_id;
    }else{
      const data1 = this.getData.filter(el => el.question.question_id != submitFileds.question_id.value);
      this.setData = data1;
      this.setData = [...react.getData, data];
      this.setQuestionid = data.question_id;
      document.getElementById("question_id").value = 0;
    }

    

    
    
    
  },

  render(){
    return `<style>
      .text-left > * {
        text-align: left !important;
        direction: ltr !important;
      }
    
      .text-right > * {
        text-align: right !important;
        direction: rtl !important;
      }
    </style>
    ${this.generatRow()}
    `;
  },

  mount(){
    const layout = this.render();
    const table = document.getElementById("table_question");
    table.innerHTML = null;
    table.insertAdjacentHTML('beforeend', layout);

    for (key in clearFileds){
      clearFileds[key].value = null;
    }
    document.querySelectorAll('.note-editable').forEach(el => el.innerHTML = null)
  },
}

save_and_next.addEventListener('click' , function () {
  const form = $("#question_form").validate();
  react.saveData();
})

document.getElementById("question_form").addEventListener("submit", (e) => {
  e.preventDefault();
})

