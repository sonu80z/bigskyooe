<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .container {
            width: 100%;
            margin: 0px auto;
            margin-top: -3rem;
        }
        .signline {
            border-top: 2px solid #ccc;
            text-align: center;
            display: block;
            margin-top: 20px;
        }
        em {
            font-size: 0.6rem;
        }

        .phy-sign {
            font-size: 0.9rem;
        }
        .phy-sign > .line {
            display: inline-block;
            border-bottom: 2px solid #ccc;
            width: 300px;
            margin-left: 120px; 
        }

        table {
            table-layout: fixed;
        }

        table td {
            word-break: break-all;
            word-wrap: break-word;
            vertical-align: top;
            font-size: 0.7rem;
        }
        table th {
            vertical-align: top;
        }

        table strong {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .strg {
            font-size: 0.9rem;
        }

        .strog {
            font-size: 1.2rem;
        }

        .border-div {
            padding: 5px;
            margin: 5px;
            border: solid 1px #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <table style="width: 100%; margin-bottom: 5px;">
            <tr>
                <td style="width: 50%; height: 150px;">
                    <div style="display: inline-block;">
                        <img src="{logo-img}" alt="" style="margin-top: -1rem;">
                        <div style="margin-left: 270px; font-size: 0.9rem; margin-top: -120px; margin-bottom: 30px;">
                            Phone: <br/>412.88109333<br/><br/>
                            FAX: <br/>412.881.3522
                        </div>
                    </div>
                    <div style="margin-left: 10px; font-weight: 600; font-size: 0.9rem;">4684 Clairton Boulevard • Pittsburgh, PA 15236</div>
                </td>
                <!-- <td style="width: 50%;">
                    <div style="border-left: solid 2px #333; border-bottom: solid 2px #333; height: 150px; margin-left: 50px; width: 300px;"></div>
                </td> -->
            </tr>
        </table>
        <div class="order-date border-div" style="margin-bottom: 5px; text-align: center;">
            <strong class="strog">Requested date of service: </strong><?php echo date('m/d/Y', strtotime($order['date_of_service'])); ?>
        </div>
        <div class="border-div">
            <div><strong class="strg">Facility / Organization: </strong><?php echo ($facilityname->facility_name) ? $facilityname->facility_name : ''; ?></div>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td>Station: <?php echo ($order['orderedstation']) ? $order['orderedstation'] : ''; ?></td>
                    <td>Rm: <?php echo ($order['orderedroom']) ? $order['orderedroom'] : ''; ?></td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td style="width: 30%;"><div style="float: left;">Address: </div> <div style="margin-left: 20px;"><?php echo ($order['orderedaddress']) ? $order['orderedaddress'] : ''; ?></div></td>
                    <td style="width: 27%;">City: <?php echo ($order['orderedcity']) ? $order['orderedcity'] : ''; ?></td>
                    <td style="width: 27%">State: <?php echo ($state->fldState) ? $state->fldState : ''; ?></td>
                    <td style="width: 16%;">Zip: <?php echo ($order['orderedzip']) ? $order['orderedzip'] : ''; ?></td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td>Phone: <?php echo ($order['orderedphone']) ? $order['orderedphone'] : ''; ?></td>
                    <td>Add Cell: </td>
                    <td>FAX: <?php echo ($order['orderedfax']) ? $order['orderedfax'] : ''; ?></td>
                </tr>
            </table>
        </div>
        <?php
            if($order['orderedroom'] && $order['orderedroom'] == 'IN HOME REQUEST') {
        ?>
        <div class="border-div">
            <div><strong class="strg">Physical Address: </div>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td>Address: </td>
                    <td>Alt contact: </td>
                </tr>
            </table>
        </div>
        <?php
            }
        ?>
        <div class="border-div">
            <strong class="strg">Patient Information:</strong>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <?php
                        $pname = '';
                        if($order['firstname']) {
                            $pname .= $order['firstname'];
                        }
                        if($order['middlename']) {
                            if($pname) {
                                $pname .= ' ' . $order['firstname'];
                            } else {
                                $pname .= $order['middlename'];
                            }
                        }
                        if($order['lastname']) {
                            if($pname) {
                                $pname .= ' ' . $order['lastname'];
                            } else {
                                $pname .= $order['lastname'];
                            }
                        }
                    ?>
                    <td colspan="2">Patient Name: <?php echo $pname; ?></td>
                    <td><span>Male</span> <input type="checkbox" <?php if($order['gender'] == 'M') { echo 'checked="checked"'; } ?> /> <span style="margin-left: 30px">Female</span> <input type="checkbox" <?php if($order['gender'] == 'F') { echo 'checked="checked"'; } ?> /> </td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td>MR# <?php echo ($order['patientmr']) ? $order['patientmr'] : ''; ?></td>
                    <td>SS# <?php echo ($order['patientssn']) ? $order['patientssn'] : ''; ?></td>
                    <td>DOB: <?php echo ($order['dob']) ? $order['dob'] : ''; ?></td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td colspan="2">MBI M/Care# <?php echo ($order['mbi_medicare']) ? $order['mbi_medicare'] : ''; ?></td>
                    <td>ACCESS# </td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td colspan="3">Other INS. NAME: <?php echo ($order['insurancecompany']) ? $order['insurancecompany'] : ''; ?></td>
                </tr>
            </table>
        </div>
        <div class="border-div">
            <strong class="strg">Responsible Party:</strong>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td colspan="2">Name: <?php ($order['responsible_party']) ? $order['responsible_party'] : ''; ?></td>
                    <?php
                        $raddress = '';
                        if($order['address1']) {
                            $raddress .= $order['address1'];
                        }
                        if($order['address2']) {
                            if($raddress) {
                                $raddress .= '<br/>' . $order['address2'];
                            } else {
                                $raddress .= $order['address2'];
                            }
                        }
                    ?>
                    <td colspan="4">Address: <?php echo $raddress; ?></td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td colspan="2"><span class="signline">Patient Signature</span></td>
                    <td colspan="2"></td>
                    <td colspan="2"><span class="signline">Witness's Signature</span></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <em style="font-size: 14px;">I request that payment of authorized Medicare benefits be made either to me or on my behalf to Tri-State Mobile X-Ray, Inc., for any services furnished me by that physician or supplier I authorize any holder of medical information about me to release to the Health Care Financing Administration and its agents any information needed to determine these benefits or the benefits payable for related services.</em>
                    </td>
                </tr>
            </table>
        </div>
        <div class="border-div">
            <strong class="strg">PROCEDURES: </strong>
            <table style="width: 100%; font-size: 14px;">
                <?php
                    if(sizeof($procedurelist) > 0) {
                        for($i = 0; $i < sizeof($procedurelist); $i++) {
                ?>
                <tr>
                    <td style="width: 45%;">Procedure #<?php echo $i + 1; ?>: <?php echo $procedurelist[$i]['procedure']; ?></td>
                    <td style="width: 5%;"><?php echo $procedurelist[$i]['plrn']; ?></td>
                    <td style="width: 50%;"><div style="float: left;">Symptoms: </div><div style="margin-left: 60px;"><?php echo $procedurelist[$i]['symptom']; ?></div></td>
                </tr>
                <?php
                        }
                    }
                ?>
            </table>
        </div>
        <?php
            $phyname = '';
            if($drname->firstname) {
                $phyname .= $drname->firstname;
            }
            if($drname->lastname) {
                if($phyname) {
                    $phyname .= ' ' . $drname->lastname;
                } else {
                    $phyname .= $drname->lastname;
                }
            }
        ?>
        <div class="border-div">
            <div><strong class="strg">Physician Name: </strong><?php echo $phyname; ?></div>
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td colspan="3"><span class="signline">Physician Signature</span></td>
                    <td colspan="1"></td>
                    <td colspan="3"><span class="signline">Date of Signature</span></td>
                </tr>
                </tr>
            </table>
        </div>
        <div class="border-div">
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td colspan="2">Does the patient have reason to believe she is pregnant? </td>
                    <td><span>Yes</span> <input type="checkbox" <?php if($order['female_pregnant']) { echo 'checked="checked"'; } ?> /> <span style="margin-left: 30px">No</span> <input type="checkbox" <?php if(!$order['female_pregnant']) { echo 'checked="checked"'; } ?> /> </td>
                </tr>
                <tr></tr><tr></tr>
                <tr>
                    <td colspan="2">Protective Shielding Used? </td>
                    <td><span>Yes</span> <input type="checkbox" <?php if($order['protective_shielding_used'] == 'checked') { echo 'checked="checked"'; } ?> /> <span style="margin-left: 30px">No</span> <input type="checkbox" <?php if($order['protective_shielding_used'] != 'checked') { echo 'checked="checked"'; } ?> /> </td>
                </tr>
            </table>
            <table style="width: 45%; margin-top: 10px;">
                <tr>
                    <td class="phy-sign">Date Completed: <span class="line"></span></td>
                </tr>
            </table>
            <table style="width: 45%; margin-top: 10px;">
                <tr>
                    <td class="phy-sign">Technician: <span class="line"></span></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>