<?php

// 40 じゃんけんを作成しよう！
// 下記の要件を満たす「じゃんけんプログラム」を開発してください。
// 要件定義
// ・使用可能な手はグー、チョキ、パー
// ・勝ち負けは、通常のじゃんけん
// ・PHPファイルの実行はコマンドラインから。
// ご自身が自由に設計して、プログラムを書いてみましょう！


//じゃんけんの出し手の設定
const ROCK = 0;
const SCISSORS = 1;
const PAPER = 2;
const HAND_TYPE = [
  ROCK => 'グー',
  SCISSORS => 'チョキ', 
  PAPER => 'パー'
];

//じゃんけんの勝敗結果の設定
const DRAW = 0;
const LOSE = 1;
const WIN = 2;

//じゃんけん続行選択の設定
const QUIT = 'q';
const CONT = 'c';

//メッセージの設定
define('MESSAGES',
  array(
    'INPUT_HAND' => sprintf("グー : %s  /  チョキ : %s  / パー : %s", ROCK, SCISSORS, PAPER). PHP_EOL . 'じゃんけんの出し手を入力してください >>> ',
    'CONTINUE_SELECT' => sprintf("じゃんけんを続けますか？ (やめる : %s / 続ける : %s) ", QUIT, CONT),
    'THANKS'  => 'ありがとうございました'
));
  
//結果表示するメッセージの設定
const RESULT_MESSAGES = array(
  DRAW => 'あいこです。もう１回',
  LOSE => 'あなたの負けです',
  WIN => 'あなたの勝ちです',
);

//エラーメッセージの設定
const ERROR_MESSAGES = array(
  'HAND' => "! Error : 0,1または2で入力してください",
  'CONTINUE' => "! Error : 半角英字の「q」または「c」で入力してください",
);


/**
 * プレイヤーの出し手を取得
 * @return int 標準入力された 0 (= グー), 1 (= チョキ), 2 (= パー) を返す
 */
function getPlayerHand(){
  echo PHP_EOL . MESSAGES['INPUT_HAND'] . PHP_EOL;
  $input = trim(fgets(STDIN));

  if(isset(HAND_TYPE[$input]) === false){
      echo PHP_EOL . ERROR_MESSAGES['HAND'] . PHP_EOL;
      return getPlayerHand(); //入力NGなら再帰処理
  }
  return $input;
}


/**
 * コンピュータの出し手を取得
 * @return int ランダムに 0 (= グー), 1 (= チョキ), 2 (= パー) を返す
 */
function getComHand(){
  return rand(0, 2);
}


/**
 * 勝敗を判定
 * @param int $playerhand プレイヤーの出し手の数字 (0 = グー, 1 = チョキ, 2 = パー)
 * @param int $comhand コンピュータの出し手の数字 (0 = グー, 1 = チョキ, 2 = パー)
 * @return int 判定結果を、0 = 引き分け, 1 = プレイヤーの負け, 2 = プレイヤーの勝ち で返す
 */
function judge($playerhand, $comhand) {
  echo PHP_EOL . 'あなた : ' . HAND_TYPE[$playerhand] . PHP_EOL;
  echo 'コンピュータ : ' . HAND_TYPE[$comhand] . PHP_EOL;

  return ($playerhand - $comhand + 3) % 3; 
}


/**
 * 結果を表示
 * @param int $result 勝敗の判定結果  0 = 引き分け, 1 = プレイヤーの負け, 2 = プレイヤーの勝ち
 * @return int 判定結果に応じてコメントを出力。あいこの場合は、メイン関数を再帰処理。
 */
function show($result){
  echo PHP_EOL . RESULT_MESSAGES[$result] . PHP_EOL;
}


/**
 * ゲームを続けるかどうか選択
 * @return function 続けない場合はコメントを出力して終了。ゲームを続ける場合は、メイン関数を再帰処理。
 */
function selectContinue(){  
  echo PHP_EOL . MESSAGES['CONTINUE_SELECT'] . PHP_EOL;
  $input = trim(fgets(STDIN));

  if($input === QUIT){
      echo PHP_EOL . MESSAGES['THANKS'] . PHP_EOL;
      exit();
  } else if($input === CONT){
      return main(); //ゲームを続ける場合はメイン処理を再度実行
  } else{
      echo PHP_EOL . ERROR_MESSAGES['CONTINUE'] . PHP_EOL;
      return selectContinue(); //入力NGの場合は、再帰処理
  }
}


/**
 * 一連の流れを行うメイン処理
 */
function main(){
  $playerhand = getPlayerHand();
  $comhand = getComHand();
  
  //勝敗を判定
  $result = judge($playerhand, $comhand);
  
  //結果を表示
  show($result);

  //あいこの場合はもう１回
  if($result === DRAW){
    main();
  }

  //ゲームを続けるか選択
  selectContinue();
}

main(); //メイン処理実行

?>