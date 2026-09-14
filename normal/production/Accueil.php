<style>
    .card-stat {
        border: none;
        border-radius: 15px;
        color: #fff;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .card-stat:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.2);
    }
    .card-stat i {
        font-size: 45px;
        position: absolute;
        top: 20px;
        right: 20px;
        opacity: 0.2;
    }
    .card-title {
        font-size: 18px;
        margin-top: 10px;
        font-weight: 500;
    }
    .count {
        font-size: 40px;
        font-weight: 700;
    }
    .bg-total { background: linear-gradient(135deg, #e74c3c, #ff7675); }
    .bg-garcons { background: linear-gradient(135deg, #2980b9, #6dd5fa); }
    .bg-filles { background: linear-gradient(135deg, #8e44ad, #c471ed); }
    .bg-effectif { background: linear-gradient(135deg, #27ae60, #2ecc71); }
</style>
<div class="row" style="padding:10px;">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:15px;">
        <div class="card-stat bg-total">
            <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
            <div class="count"><?php echo getNbreInscritTotal($_SESSION['idanneescolaire'],$pdo);?></div>
            <h3><span class="card-title">Total El&egrave;ve(s)</span></h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:15px;">
        <div class="card-stat bg-garcons">
            <div class="icon"><i class="fa fa-comments-o"></i></div>
            <div class="count"><?php echo getNbreInscritParSexe($_SESSION['idanneescolaire'],1,"Masculin",$pdo);?></div>
            <h3><span class="card-title">Total Gar&ccedil;on(s)</span></h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:15px;">
        <div class="card-stat bg-filles">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
            <div class="count"><?php echo getNbreInscritParSexe($_SESSION['idanneescolaire'],1,"Feminin",$pdo);?></div>
            <h3><span class="card-title">Total Fille(s)</span></h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:15px;">
        <div class="card-stat bg-effectif">
            <div class="icon"><i class="fa fa-check-square-o"></i></div>
            <div class="count"><?php echo getNbreInscritTotal($_SESSION['idanneescolaire'],$pdo)-getNbreEleveAbandonne($_SESSION['idanneescolaire'],0,$pdo);?></div>
            <h3><span class="card-title">Effectif actuel</span></h3>
        </div>
    </div>
</div>
<div style="text-align:center;padding:10px;">
    <img src="images/etudiantN.jpg" style="border-radius:75px;max-width:100%;height:auto;"/>
</div>