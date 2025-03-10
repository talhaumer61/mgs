<style>
    .paper{
        display: block;
        width: 100%;
        color: black;
        font-family: Arial, serif ;
    }

    .paper-body-objective{
        position: relative;
    }

    .paper-header{
        display: flex;
        /*align-items: flex-start;*/
        justify-content: space-between;
        font-size: 16px;
        margin-bottom: 50px;
    }

    .paper-header-item{
        display: flex;
        align-items: center;
        gap: 20px;
        margin: 16px 0;
    }

    .paper-header-item-bold{
        font-weight: bold;
    }

    .paper-header-item-underline{
        width: 100%;
        align-self: stretch;
        border-bottom: 1px solid black;

    }

    .paper-subjective-question > p,
    .paper-objective-question{
        font-size: 16px;
        color: black;
    }

    .heading{
        /*text-align: center;*/
        font-size: 20px;
        color: black;
        margin-bottom: 20px !important;
    }

    .question-number{
        margin-right: 5px;
    }

    .question-marks{
        font-weight: bold;
        font-size: 14px !important;
    }

    .paper-objective-question-options{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        margin-left: 20px;
    }

    .paper-objective-question-options p{
        margin-left: 20px;
    }

    .paper-question-box{
        padding: 10px 0 !important;
    }

    .paper-body-subjective{
        margin-top: 40px !important;
    }

    .paper-subjective-question{
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .paper-change-btn{
        position: absolute;
        top: -30%;
        right: 0;
        width: 200px;
    }


    .paper-logo-container{
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .paper-logo{
        width: 100px;
        height: 100px;
    }

    .flex-reverse{
        flex-direction: row-reverse !important;
    }

</style>

<section role="main" class="content-body">

  <header class="page-header">
    <h2>Generated Paper</h2>
  </header>

  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-plus-square"></i> Generated Paper</h4>
    </div>

    <div class="panel-body">
        <div class="paper">
            <div class="paper-body">
                <h1 class="heading">Objective Part</h1>
                <div class="paper-body-objective">
                    <?php for($i=0; $i < sizeof($data['objective']); $i++ ) : ?>
                        <div class="paper-question-box">
                            <p class="paper-objective-question"><span class="question-number"><?=$i+1?> )</span><?=$data['objective'][$i]['question']->question?></p>
                            <div class="paper-objective-question-options">
                                <?php for($j=0; $j < sizeof($data['objective'][$i]['question']->answers_options); $j++) : ?>
                                    <p dir="<?= !$data['objective'][$i]['question']->is_ltr ? 'ltr' : 'rtl';  ?>" ><span class="question-number">(<?=$j+1?>)</span>  <?=$data['objective'][$i]['question']->answers_options[$j]->answer_option?></p>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="paper-body-subjective">
                    <h1 class="heading">Subjective Part</h1>

                    <?php for($i=0; $i < sizeof($data['subjective']); $i++) : ?>
                    <div class="paper-question-box">
                        <?php
                        $is_ltr = $data['subjective'][$i]->dir_ltr;
                        $class = "";
                        if ($is_ltr)
                            $class = "flex-reverse";
                        ?>

                        <div class="paper-subjective-question <?=$class?>"  >
                            <p dir="<?= !$is_ltr ? 'ltr' : 'rtl';  ?>"  ><span class="question-number"><?=$i+1?> )</span> <?=$data['subjective'][$i]->question?></p>
                            <p class="question-marks">(<?=$data['subjective'][$i]->marks?>)</p>
                        </div>

                        <ol  style="margin: 0 20px; list-style-type: lower-roman">
                          <?php foreach ($data['subjective'][$i]->sub_questions as $sub_question): ?>
                              <li style="display: list-item" >
                                  <div class="paper-subjective-question <?=$class?>"  >
                                      <p dir="<?= !$is_ltr ? 'ltr' : 'rtl';  ?>"  ></span><?=$sub_question->question?></p>
                                      <p class="question-marks">(<?=$sub_question->marks?>)</p>
                                  </div>
                              </li>
                          <?php endforeach; ?>
                        </ol>

                    </div>
                   <?php endfor; ?>

                </div>
            </div>
        </div>

        <div class="col-md-12 text-center" style="margin-top: 50px">
            <?php if(isset($data['paper_id']) && $data['paper_id']) : ?>
                <a type="submit" href="<?=URLROOT?>/papers/save_generated/<?php echo $data['paper_id']?>" class="mr-xs btn btn-primary">Save Paper</a>
            <?php else: ?>
                <a type="submit" href="<?=URLROOT?>/papers/save_generated" class="mr-xs btn btn-primary">Save Paper</a>
            <?php endif; ?>
        </div>
    </div>

</section>
    </div>
  </div>
