<?php
class m_offers
{
    private $conn;
    function m_offers($conn)
    {
        $this->conn = $conn;
    }
    function getOfferById($offerId)
    {
        $stmt = $this->conn->prepare("  SELECT * FROM `trade_offers` 
                                        WHERE   `offer_id` = ?");
        $stmt->bind_param("s", $offerId);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insertOffer($tradeId, $courseId)
    {
        $stmt = $this->conn->prepare("INSERT    INTO   `trade_offers`(`offer_id`, `trade_id`, `offer_student_id`, `offer_course_id`, `status`) 
                                                VALUES  (
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?)");
        $user = $_SESSION["login_usr"];
        $status = "Pending";
        $offer_id = sha1(microtime(true) . mt_rand(10000, 90000));;
        $stmt->bind_param("sssss", $offer_id, $tradeId, $user, $courseId, $status);
        $stmt->execute();
    }
    function determineTradeOffer($tradeId)
    {
        $stmt = $this->conn->prepare("  SELECT  `courses`.`course_id`, 
                                                `courses`.`name`   
                                        FROM    `assigned_courses` 
                                        JOIN    `courses`   ON `assigned_courses`.`course_id` = `courses`.`course_id` 
                                        WHERE   `courses`.`package` IN (
                                                                        SELECT  `package` 
                                                                        FROM    `courses` 
                                                                        WHERE   `course_id` IN (
                                                                                                SELECT  `donor_course_id` 
                                                                                                FROM    `trades` 
                                                                                                WHERE   `trade_id` = ?
                                                                                            )
                                                                    )
                                        AND     `courses`.`year`    IN (
                                                                        SELECT  `year` 
                                                                        FROM    `courses` 
                                                                        WHERE   `course_id` IN (
                                                                                                SELECT  `donor_course_id` 
                                                                                                FROM    `trades` 
                                                                                                WHERE   `trade_id` = ?
                                                                                            )
                                                                    )
                                        AND     `student_id` = ?");

        $user = $_SESSION["login_usr"];
        $stmt->bind_param("sss", $tradeId, $tradeId, $user);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getOffersForTrade($tradeId)
    {
        $stmt = $this->conn->prepare("  SELECT COUNT(*) FROM `trade_offers` 
                                        WHERE   `trade_id` = ? 
                                        AND     `offer_student_id` = ?");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("ss", $tradeId, $user);
        $stmt->execute();
        return $stmt->get_result();
    }

    function checkValidOffer($tradeId, $courseId)
    {
        $stmt = $this->conn->prepare("  SELECT COUNT(*) FROM `trade_options` 
                                        WHERE   `trade_id` = ? 
                                        AND     `option_course_id` = ?");
        $stmt->bind_param("ss", $tradeId, $courseId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getTradeStatus($tradeId)
    {
        $stmt = $this->conn->prepare("  SELECT  `status` FROM `trades` 
                                        WHERE   `trade_id` = ?");
        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getOffers()
    {
        $stmt = $this->conn->prepare("  SELECT * FROM   `trades` 
                                        JOIN            `trade_offers`
                                        ON              `trades`.`trade_id` = `trade_offers`.`trade_id`
                                        WHERE           `donor_student_id`  = ?
                                        AND             `trade_offers`.`status` = 'Pending'");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $tradeOffers = array();
        while ($row = $result->fetch_assoc()) {
            $tradeOffer["offer_id"] = $row["offer_id"];
            $stmt = $this->conn->prepare("  SELECT  `name` 
                                            FROM    `courses` 
                                            WHERE   `course_id` = ?");
            $stmt->bind_param("s", $row["donor_course_id"]);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $tradeOffer["donor_course_name"] = $result2->fetch_assoc()["name"];

            $stmt->bind_param("s", $row["offer_course_id"]);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $tradeOffer["offer_course_name"] = $result2->fetch_assoc()["name"];

            $stmt = $this->conn->prepare("  SELECT  `username` 
                                            FROM    `students` 
                                            WHERE   `student_id` = ?");
            $stmt->bind_param("s", $row["offer_student_id"]);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $tradeOffer["offer_student_name"] = $result2->fetch_assoc()["username"];

            //Add rows in Array
            $tradeOffers[] = $tradeOffer;
        }

        return ($tradeOffers);
    }

    function getOffersNumber()
    {
        $stmt = $this->conn->prepare("  SELECT COUNT(*) FROM    `trades` 
                                                        JOIN    `trade_offers`
                                                        ON      `trades`.`trade_id` = `trade_offers`.`trade_id`
                                                        WHERE   `donor_student_id`  = ?
                                                        AND     `trade_offers`.`status` = 'Pending'");
        $user = $_SESSION["login_usr"];
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()["COUNT(*)"];
    }

    function acceptOffer($offerId)
    {
        //TODO: Cand oferta este acceptata, pune cursul din oferta in trade. Schimba status la trade in Secretary. Invalideaza restul ofertelor
        //Muta logica de schimbare cursuri (assigned courses) in trades/AcceptTradeRequest

        //Accepta oferta
        $stmt = $this->conn->prepare("  UPDATE  `trade_offers`
                                        SET     `status` = 'Accepted'
                                        WHERE   `offer_id` = ?");

        $stmt->bind_param("s", $offerId);
        $stmt->execute();
        $stmt->close();

        //Selecteaza datele despre aceasta oferta(cea acceptata)
        $stmt = $this->conn->prepare("  SELECT  *
                                        FROM    `trade_offers` 
                                        WHERE   `offer_id` = ?");

        $stmt->bind_param("s", $offerId);
        $stmt->execute();

        $result = $stmt->get_result();
        $result = $result->fetch_assoc();

        $tradeId        = $result["trade_id"];
        $offerCourseId  = $result["offer_course_id"];
        $offerStudentId = $result["offer_student_id"];

        $stmt->close();

        //Invalideaza restul ofertelor pe acest trade
        $stmt = $this->conn->prepare("  UPDATE  `trade_offers`
                                        SET     `status` = 'Declined'
                                        WHERE   `trade_id` = ?
                                        AND     `status` <> 'Accepted' ");

        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        $stmt->close();

        //Seteaza receiver course si receiver student in trade. Pune status Secretary
        $stmt = $this->conn->prepare("  UPDATE  `trades`
                                        SET     `receiver_course_id` = ?, 
                                                `receiver_student_id` = ?,
                                                `status` = 'Secretary'
                                        WHERE   `trade_id` = ?");

        $stmt->bind_param("sss", $offerCourseId, $offerStudentId, $tradeId);
        $stmt->execute();
        $stmt->close();

        //Selecteaza datele despre donor student/course
        $stmt = $this->conn->prepare("  SELECT  `donor_student_id`,
                                                `donor_course_id`
                                        FROM    `trades`
                                        WHERE   `trade_id` = ?");

        $stmt->bind_param("s", $tradeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();

        $donorCourseId  = $result["donor_course_id"];
        $donorStudentId = $result["donor_student_id"];

        $stmt->close();

        //Schimba cursurile pentru Donor Student
        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses`
                                        SET     `course_id`  = ?, 
                                                `status`     = 'Traded'
                                        WHERE   `student_id` = ?
                                        AND     `course_id`  = ?");

        $stmt->bind_param("sss", $offerCourseId, $donorStudentId, $donorCourseId);
        $stmt->execute();
        $stmt->close();

        //Schimba cursurile pentru Receiver Student
        $stmt = $this->conn->prepare("  UPDATE  `assigned_courses`
                                        SET     `course_id`  = ?, 
                                                `status`     = 'Traded'
                                        WHERE   `student_id` = ?
                                        AND     `course_id`  = ?");

        $stmt->bind_param("sss", $donorCourseId, $offerStudentId, $offerCourseId);
        $stmt->execute();
        $stmt->close();
    }

    function declineOffer($offerId)
    {
        $stmt = $this->conn->prepare("  UPDATE  `trade_offers`
                                        SET     `status` = 'Declined'
                                        WHERE   `offer_id` = ?");

        $stmt->bind_param("s", $offerId);
        $stmt->execute();
        $stmt->close();
    }
}
