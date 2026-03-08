<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/procedure/lists'); ?>" class="btn btn-default">PROCEDURE LIST</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body my-form-body a_u_a_top_dv">
                <?php echo form_open(base_url('admin/procedure/create/'.$id), 'class="form-horizontal"');  ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">CPT Code * </label>
                        <input type="text" name="cpt_code" class="form-control" value="<?=isset($id)?$procedure["cpt_code"]:""?>" required />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Description * </label>
                        <input type="text" name="description" value="<?=isset($id)?$procedure["description"]:""?>" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Modality * </label>
                        <select name="modality" class="form-control">
                            <option value="ECHO" <?=(isset($id)?(($procedure["modality"]=="ECHO")?"selected":""):"")?>>ECHO</option>
                            <option value="EKG" <?=(isset($id)?(($procedure["modality"]=="EKG")?"selected":""):"")?>>EKG</option>
                            <option value="MR" <?=(isset($id)?(($procedure["modality"]=="MR")?"selected":""):"")?>>MR</option>
                            <option value="US" <?=(isset($id)?(($procedure["modality"]=="US")?"selected":""):"")?>>US</option>
                            <option value="X-RAY" <?=(isset($id)?(($procedure["modality"]=="X-RAY")?"selected":""):"")?>>X-RAY</option>
                            <option value="Lab" <?=(isset($id)?(($procedure["modality"]=="Lab")?"selected":""):"")?>>Lab</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Category </label>
                        <select name="category" class="form-control">
                            <option value="Test Value" <?=(isset($id)?(($procedure["category"]=="Test Value")?"selected":""):"")?>>Test Value</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Symptoms </label>
                        <select name="symptoms_slt" class="form-control" multiple data-live-search="true">
                            <?php
                            $symptoms = array(
                            "R14.0 Abdominal distension (gaseous)",
                            "R09.3  Abnormal sputum",
                            "R63.4  Abnormal weight loss",
                            "R19.11  Absent bowel sounds",
                            "R10.0  Acute abdomen",
                            "G89.11  Acute pain due to trauma",
                            "I72.9  Aneurysm of unspecified site",
                            "M12.9  Arthropathy, unspecified",
                            "J69.0 ASPIRATION PNEUMONITIS",
                            "J98.11  Atelectasis",
                            "R00.1 Bradycardia, unspecified",
                            "N20.0  Calculus of kidney",
                            "N20.2  Calculus of kidney with calculus of ureter",
                            "I49.9  Cardiac arrythmia, unspecified",
                            "I51.7  Cardiomegaly",
                            "G56.00  Carpal tunnel syndrome, unspecified upper limb",
                            "R19.4  Change in bowel habit",
                            "R07.1  Chest pain on breathing",
                            "R07.9  Chest pain, unspecified",
                            "J44.9  Chronic obstructive pulmonary disease, unspecified",
                            "G89.21 Chronic pain due to trauma",
                            "Q76.2  Congenital spondyloisthesis",
                            "R09.89 CONGESTION / CHEST SOUNDS",
                            "K59.00 Constipation, unspecified",
                            "R05  Cough",
                            "N44.1  Cyst of tunica albuginea testis",
                            "R42  Dizziness and giddiness",
                            "M54.9  Dorsalgia, unspecefied",
                            "R06.00 Dyspnea, unspecified",
                            "R60.9  Edema unspecified",
                            "Z01.818  Encounter for other preprocedural examination",
                            "Z48.89  Encounter for other specified surgical aftercare",
                            "Z01.810  Encounter for preprocedural cardiovascular examination",
                            "Z01.811  Encounter for preprocedural respiratory examination",
                            "Z12.31  Encounter for screening mammogram for malignant neoplasm of breast",
                            "Z51.81 Encounter for therapeutic drug level monitoring",
                            "Z11.1  Encounter screening for respiratory tuberculosis",
                            "R10.13  Epigastric pain",
                            "W19.XXXA  Fall, unspecified initial encounter",
                            "R53.83  Fatigue, other",
                            "R50.9  Fever, unspecified",
                            "E887 Fracture, cause unspecified",
                            "Acute post-traumatic headache, not intractable G44.319",
                            "R14.1  Gas pain",
                            "R10.84  Generalized abdominal pain",
                            "R60.1  Generalized Edema",
                            "Retained Foreign Body following Wound of bilateral Orbits H05.3",
                            "Other Disorders Orbit H05.89",
                            "I50.9  Heart failure, unspecified",
                            "R04.2  Hemoptysis",
                            "R19.12  Hyperactive bowel sounds",
                            "R09.02  Hypoxemia",
                            "CONSTIPATION K59.09",
                            "R10.32 Left lower quadrant pain",
                            "R10.12  Left upper quadrant pain",
                            "R60.0  Localized Edema",
                            "R22.0  Localized swelling, mass and lump, head",
                            "R22.42  Localized swelling, mass and lump, left lower limb",
                            "R22.32  Localized swelling, mass and lump, left upper limb",
                            "R22.43  Localized swelling, mass and lump, lower limb, bilateral",
                            "R22.1 Localized swelling, mass and lump, neck",
                            "R22.41  Localized swelling, mass and lump, right lower limb",
                            "R22.31 Localized swelling, mass and lump, right upper limb",
                            "R22.2  Localized swelling, mass and lump, trunk",
                            "R22.44  Localized swelling, mass and lump, unspecified",
                            "R22.9 Localized swelling, mass and lump, unspecified",
                            "R22.40 Localized swelling, mass and lump, unspecified lower limb",
                            "R22.30  Localized swelling, mass and lump, unspecified upper limb",
                            "R22.33  Localized swelling, mass and lump, upper limb, bilateral",
                            "M54.5  Low back pain",
                            "R10.30 Lower abdominal pain, unspecified",
                            "R92.1  Mammographic calcification found on diagnostic imaging of breast",
                            "K52.838 MICROSCOPIC COLITIS",
                            "R11.0 NAUSEA",
                            "R76.11  Nonspecific reaction to skin test w/o active tuberculosis prev pos tb",
                            "I65.23  Occlusion and stenosis of bialteral carotid arteries. Carotid Bruit",
                            "R92.8  Other abnormal and inconclusive findings on dx imaging of breast",
                            "R19.15  Other abnormal bowel sounds",
                            "R06.89  Other abnormalities of breathing",
                            "R01.2  Other cardiac sounds",
                            "G89.29  Other Chronic pain",
                            "M54.89  Other dorsalgia",
                            "R06.09  Other forms of dyspnea",
                            "N44.8  Other noninflammatory disorders of the testis chemistry",
                            "R91.8  Other nonspecific abnormal finding of lung field",
                            "J98.19 Other pulmonary collapse",
                            "R79.89  Other specified abnormal findings of blood chemistry",
                            "N50.8  Other specified disorders of male genital organs",
                            "M25.572 PAIN IN LEFT ANKLE",
                            "M79.602 Pain in left arm",
                            "M25.522 PAIN IN LEFT ELBOW",
                            "M79.645  Pain in left finger(s)",
                            "M79.672  Pain in left foot",
                            "M79.632  Pain in left forearm",
                            "M79.642  Pain in left hand",
                            "M79.552 Pain in left hip",
                            "M25.562  Pain in left knee",
                            "M79.605  Pain in left leg",
                            "M79.662  Pain in left lower leg",
                            "M25.512 PAIN IN LEFT SHOULDER",
                            "M79.652 Pain in left thigh",
                            "M79.675  Pain in left toe(s)",
                            "M79.622  Pain in left upper arm",
                            "M25.532 PAIN IN LEFT WRIST",
                            "M79.606  Pain in leg, unspecified limb",
                            "M25.571  Pain in right ankle and joints of right foot",
                            "M79.601  Pain in right arm",
                            "M25.521 PAIN IN RIGHT ELBOW",
                            "M79.644  Pain in right finger(s)",
                            "M79.671  Pain in right foot",
                            "M79.631  Pain in right forearm",
                            "M79.641 Pain in right hand",
                            "M79.551  Pain in right hip",
                            "M25.561  Pain in right knee",
                            "M79.604  Pain in right leg",
                            "M79.661 Pain in right lower leg",
                            "M25.511 PAIN IN RIGHT SHOULDER",
                            "M79.651  Pain in right thigh",
                            "M79.674 Pain in right toe(s)",
                            "M79.621  Pain in right upper arm",
                            "M25.531 PAIN IN RIGHT WRIST",
                            "M54.6  Pain in Thoracic Spine",
                            "M25.579  Pain in unspecified ankle and joints of unspecified foot",
                            "M79.646  Pain in unspecified finger(s)",
                            "M79.673 Pain in unspecified foot",
                            "M79.639  Pain in unspecified forearm",
                            "M79.643  Pain in unspecified hand",
                            "M79.559 Pain in unspecified hip",
                            "M25.50 Pain in unspecified joint",
                            "M25.569  Pain in unspecified knee",
                            "M25.569  Pain in unspecified knee",
                            "M79.609  Pain in unspecified limb",
                            "M79.669 Pain in unspecified lower leg",
                            "M79.659  Pain in unspecified thigh",
                            "M79.676  Pain in unspecified toe(s)",
                            "M79.629 Pain in unspecified upper arm",
                            "R52 Pain, unspecified",
                            "M84.40XA  Pathological fracture, unspecified site for fx",
                            "M84.40XA Pathological fracture, unspecified site initial encounter for fx",
                            "M84.40XS  Pathological fracture, unspecified site, sequela",
                            "R06.3  Periodic breathing",
                            "Z87.01  Personal history of pneumonia",
                            "J91.8  Pleural effusion in other condition classified elsewhere",
                            "J90  Pleural effusion, not elsewhere classified",
                            "R07.81  Pleurodynia",
                            "J18.9  Pneumonia, unspecified organism",
                            "J93.9  Pneumothorax, unspecified",
                            "R07.2  Precordial pain",
                            "Z95.2  Presence of prosthetic heart valve",
                            "R10.31  Right lower quadrant pain",
                            "R10.11  Right upper quadrant pain",
                            "M53.3 Sacrococcyxgeal disorders, not elsewhere classfied",
                            "R06.02  Shortness of Breath",
                            "R06.83  Snoring",
                            "M43.10  Spondylolisthesis, site unspecified",
                            "M47.812  Spondylosis w/o myelopathy or radiculopathy, cervical region",
                            "M47.817  Spondylosis w/o myelopathy or radiculopathy, lumbosacral region",
                            "M47.814  Spondylosis w/o myelopathy or radiculopathy, thoracic region",
                            "M43.00 Spondylosis, site unspecified",
                            "R00.0  Tachycardia, unspecified",
                            "R06.82  Tachypnea, not elsewhere classified",
                            "R10.9  Unspecified abdominal pain",
                            "R06.9  Unspecified abnormalities of breathing",
                            "R00.9  Unspecified abnormalities of heart beat",
                            "I48.91  Unspecified atrial fibrillation",
                            "W19.XXXD  Unspecified fall, subsequent encounter, follow-up exam",
                            "S36.30XA  Unspecified injury of stomach, initial encounter",
                            "N63 Unspecified lump in breast",
                            "I50.20  Unspecified systolic (congestive) heart failure",
                            "R10.10  Upper abdominal pain, unspecified",
                            "R11.10  Vomiting, unspecified",
                            "R06.2 Wheezing");
                            $sarr = explode(",", $procedure["symptoms"]);
                            foreach ( $symptoms as $row ) {
                                $is_inc = false;
                                foreach ( $sarr as $r ) {
                                    if ( trim($row) == trim($r) ) $is_inc = true;
                                }
                                if ( $is_inc ) {
                                    echo '<option value="'.$row.'" selected>'.$row.'</option>';
                                } else {
                                    echo '<option value="'.$row.'">'.$row.'</option>';
                                }
                            }
                            ?>
                        </select>
                        <input type="hidden" name="symptoms" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 g_txt_right">
                        <input type="submit" name="submit" value="Submit" class="btn btn-info">
                        <a href="#" onclick="window.location.reload()" class="btn btn-danger" >Reset</a>
                    </div>
                </div>
                <?php echo form_close( ); ?>
            </div>
        </div>
    </div>
</section>