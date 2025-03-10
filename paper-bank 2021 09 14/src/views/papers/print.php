

<div class="paper paper-print" id="paper-print-formate">
    <style>
        .paper{
            display: block;
            width: 100%;
            color: black;
            font-family: Arial, serif ;
        }

        .flex-reverse{
            flex-direction: row-reverse !important;
        }

        .paper-print{
            background-color: white !important;
            padding: 40px 80px;
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
            width: 200px;
            align-self: stretch;
            border-bottom: 1px solid black;

        }


        .paper-subjective-question > p,
        .paper-objective-question{
            font-size: 17px;
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
            margin: 10px 0;
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

    </style>
    <div class="paper-logo-container">
        <img src="<?=URLROOT?>/assets/images/logo.png" alt="" class="paper-logo">
        <div>
            <h1 style="text-transform: uppercase;">MGS School System</h1>
            <p style="font-size: 15px; text-transform: capitalize">This is an address location of your school</p>
        </div>

    </div>

    <div class="paper-header">
        <div class="paper-header-left">
            <div class="paper-header-item">
                <div class="paper-header-item-bold">Name</div>
                <div class="paper-header-item-underline"></div>
            </div>

            <div class="paper-header-item">
                <div class="paper-header-item-bold">Class</div>
                <div class="paper-header-item-underline"><?=$data['class']['name']?></div>
            </div>

            <div class="paper-header-item">
                <div class="paper-header-item-bold">Subject</div>
                <div class="paper-header-item-underline"><?=$data['subject']['name']?></div>
            </div>
        </div>


        <div class="paper-header-right">
            <div class="paper-header-item">
                <div class="paper-header-item-bold">Teacher Name</div>
                <div class="paper-header-item-underline"><?=$data['teacher']['teacher_name']?></div>
            </div>

            <div class="paper-header-item">
                <div class="paper-header-item-bold">Date</div>
                <div class="paper-header-item-underline"> <?= date('d-m-Y') ?></div>
            </div>

            <div class="paper-header-item">
                <div class="paper-header-item-bold">Signature</div>
                <div class="paper-header-item-underline"></div>
            </div>
        </div>
    </div>

    <div class="paper-body">
        <h1 class="heading">Objective Part</h1>
        <div class="paper-body-objective">
            <?php for($i=0; $i < sizeof($data['objective']); $i++ ) : ?>
                <div class="paper-question-box">
                    <p class="paper-objective-question"><span class="question-number"><?=$i+1?> )</span> <?=$data['objective'][$i]['question']->question?></p>
                    <div class="paper-objective-question-options">
                        <?php for($j=0; $j < sizeof($data['objective'][$i]['question']->answers_options); $j++) : ?>
                            <p><span class="question-number">(<?=$j+1?>)</span>  <?=$data['objective'][$i]['question']->answers_options[$j]->answer_option?></p>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="paper-body-subjective">
            <h1 class="heading">Subjective Part</h1>
            <div class="paper-question-box">
                <?php for($i=0; $i < sizeof($data['subjective']); $i++) : ?>
                    <?php
                    $is_ltr = $data['subjective'][$i]->dir_ltr;
                    $class = "";
                    if (!$is_ltr)
                        $class = "flex-reverse";
                    ?>
                    <div style="margin-bottom: 40px !important;" >
                        <div class="paper-subjective-question <?=$class?>">
                            <p dir="<?= $is_ltr ? 'ltr' : 'rtl';  ?>" ><span class="question-number"><?=$i+1?> )</span> <?=$data['subjective'][$i]->question?></p>
                            <p class="question-marks">(<?=$data['subjective'][$i]->marks?>)</p>
                        </div>

                        <?php
                            $no_of_lines = $data['subjective'][$i]->no_of_lines ?? 2;
                            for ($j = 0; $j < $no_of_lines; $j++ ):
                        ?>
                            <div style="width: 100%; border-bottom: 1px dashed black; height: 25px; margin: 12px 0;"></div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 text-center" style="margin-top: 50px">
    <button class="mr-xs btn btn-primary" id="print-paper">Print Paper</button>
</div>

<!--<script>-->
<!--    window.addEventListener("load", function (){-->
<!--        console.log("Print is working");-->
<!---->
<!--        let body = document.querySelector('body').innerHTML;-->
<!---->
<!--        let print = document.getElementById('paper-print-formate').innerHTML;-->
<!---->
<!--        document.querySelector('body').innerHTML = print;-->
<!---->
<!--        setTimeout(window.print, 2000);-->
<!---->
<!--        document.querySelector('body').innerHTML = body;-->
<!--    });-->
<!--</script>-->
