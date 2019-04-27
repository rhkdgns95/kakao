0. DB생성
CREATE DATABASE kakao;
USE kakao;

1. 테이블 생성 - 회원가입시
CREATE TABLE users_info(
    user_no INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_name char(10) NOT NULL,
    user_id varchar(40) NOT NULL,
    user_password VARCHAR(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

2. 테이블생성 - 친구추가시
CREATE TABLE friends_list(
no INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_no INT(11) UNSIGNED NOT NULL,
friend_no INT(11) UNSIGNED NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


3. 테이블생성 - 메시지함
CREATE TABLE messages_info(
no INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
sender CHAR(10) NOT NULL,
receiver CHAR(10) NOT NULL,
send_at DATETIME NOT NULL DEFAULT NOW(),
check_at DATETIME
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

4. messages_info수정 (칼럼추가)
ALTER TABLE messages_info ADD COLUMN contents TEXT NOT NULL;

5. 값 삽입하기
INSERT INTO messages_info(sender, receiver, contents) VALUES('', '', '');

6. 상대방과 내가 보낸 메시지 전체를 찾기
SELECT * FROM messages_info WHERE (sender = :sender AND receiver = :receiver) OR (receiver = :sender AND sender = :receiver);

7. rhkdgsn95사용자가 여러사용자에게 각각 마지막으로 보낸메시지 하나씩만 출력하기.
// 하기전 학습
7-1) 제일 마지막으로 입력된(제일 최신화한) 메시지를 구하는 방법
SELECT sender, receiver, max(contents) as contents FROM messages_info WHERE sender = 'rhkdgns95';

  * group by와 distinct를 살펴보자
7-2) DISTINCT 사용
SELECT DISTINCT sender, receiver, contents FROM messages_info;
=> 전부출력됨 왜냐? 구별하는 역할을하는데 select에 입력된 구문을보라... contents라는 칼럼이있는데,
위의 3가지 조건이 충족해야한다. 즉, sender, receiver, contents 이 3개의 조건이 겹칠때(중복될때)만 걸러진다.
하지만 아래의 방법을보자

7-3) GROUP BY 사용
SELECT sender, receiver, contents FROM messages_info WHERE sender = 'rhkdgns95' GROUP BY receiver;
=> rhkdgns95가 보낸 메시지를 전부 보여주되, 받는사용자들을 구별시켜서(중복방지하여) 각각사용자 1명씩만 보여준다.

7-4) 최종목표 구하기
SELECT max(no) as no, sender, receiver, contents, send_at FROM messages_info WHERE sender ='rhkdgns95' GROUP BY receiver;
=> 실패! max(no)는 다른 쿼리 결과에 영향을 주지않고 no만 최고값을 구하므로..
group by 를 사용하면 차례대로 처음값부터 바로 그룹화를시켜서 어쩔수가없다. 다음과 같이 입력해도 어쩔수가 없다.
SELECT * FROM (SELECT no, sender, receiver, contents, send_at WHERE sender ='rhkdgns95' ORDER BY no DESC)AS subQuery GROUP BY receiver;


7-5) 두개의 쿼리문을 이용해서 해보자 조건1) sender가 rhkdgns95인사람이며, 조건2) 받는사람(receiver)이 겹치면안된다.
SELECT MAX(no), sender, receiver FROM messages_info WHERE sender = 'rhkdgns95' GROUP BY receiver;

7-6) 조건3) 최신화된 메시지들만 출력하기. (위 7-5번의 결과를 배열에 저장한뒤 배열의 갯수만큼 다음의 쿼리문을 실행시킨뒤 tag에 저장하면됨!)
SELECT * FROM messages_info WHERE no = 'no';


7-7) 생각해보니 위의방법들 절때안됨. 내가원하는것은 최신화된(마지막으로) 받거나 보낸 데이터들임 하지만,
     GROUP BY의 앞에서부터 그룹핑하는 특성으로 실패, 그래서 쿼리문을 다음과 같이 설정함.
     SELECT MAX(no) as no, sender, receiver, contents, send_at FROM messages_info WHERE sender = :sender OR receiver = :receiver GROUP BY receiver, sender ORDER BY no DESC;
     순서 1) 보낸사람과 받는사람을 그룹핑함(DISTINCT 과 다르게 구별함.)
     순서 2) 보내거나 받는사람이 'rhkdgns95'인 사람 (OR 처리)
     순서 3) no(메시지함에 주고받은 메시지들 순서)를 기준으로 내림차순(마지막으로 주고받은 데이터를 맨 위로 차례로 뽑기위해서)

 /* php 구문
    $sql = "SELECT MAX(no) as no, sender, receiver, contents, send_at FROM messages_info WHERE sender = :sender OR receiver = :receiver GROUP BY receiver, sender ORDER BY no DESC";
    $prepare = $db->prepare($sql);
    $prepare->bindValue(':sender', $user_id, PDO::PARAM_STR);
    $prepare->bindValue(':receiver', $user_id, PDO::PARAM_STR);
    $prepare->execute();
    $results = $prepare->fetchAll(PDO::FETCH_ASSOC);

    $len = count($results);
    $msg_numbers = [];

    foreach($results as $key => $value)
    {
        $isInsert = 0;
        for($i = 0; $i < $len; $i++)
        {
            if($key == $i)
                continue;
            if($results[$i]['sender'] == $results[$key]['receiver'] && $results[$i]['receiver'] == $results[$key]['sender'])
            {
                $isInsert = 1;
                // receiver와 sender가 동일한경우, 해당 Message의 number를 구한다.
                // 중복되는 값들 중 큰 값을 구해야함. (최신데이터 이므로)

                if($results[$key]['no'] > $results[$i]['no'])
                    $num = $results[$key]['no'];
                else
                    continue;

                if(!in_array($num, $msg_numbers)){  // $num이 Message번호를 모아놓은곳에 있는지 확인한다. (있다면 true반환 / 없으면 false를 반환)
                        $msg_numbers[] = $num;
                }

            }
        }
        if($isInsert == 0 ) { // 중복된 값이 없을때,
            $msg_numbers[] = $value['no'];
        }
    }
    $unique_msgs = array_unique($msg_numbers);
    $list_result = [];
  */
=> 이과정이 끝나면, 이제 $unique_msgs에는 rhkdgns95 사용자와 주고받은 최신 메시지의 번호들만 남게된다.(중복 제거됨!).
=> 이제, $unique_msgs의 갯수 만큼 쿼리문에 값을 binding해서 실행시켜주면된다~


