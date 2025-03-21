<?php
/**
 * @package    com_hecbiosim_calculator
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

$params  = $this->item->params;

?>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>

<style>
.hec-calculator label{
    font-weight:bold;
}

.hec-calculator select, .hec-calculator input{
    display:block;
    line-height: inherit;
    background-color: transparent;
    border: 1px solid #dfe3e7;
    border-radius: 0.25em;
    padding: 0.33em 0.33em 0.33em 0.33em;
    font-family: inherit;
    font-size: inherit;
    margin-bottom:0.5em;
    width:100%;
    height:2rem;
    box-sizing:border-box;
}

#results i{
min-width:16px;
text-align:right;
}

.ns-day i{color:var(--teal)}
.storage-gb i{color:var(--purple)}
.node-hours i{color:var(--green)}
.max-nodes i{color:var(--yellow)}
.power-kwh i{color:orange}
.message{color:var(--danger)}
</style>

<!--<link rel="stylesheet" href="https://dev.hecbiosim.ac.uk/media/templates/site/cassiopeia/css/template.min.css"> -->
<!-- <link rel="stylesheet" href="https://dev.hecbiosim.ac.uk/media/system/css/joomla-fontawesome.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container">
   <div class="card">
      <div class="card-body hec-calculator">
         <noscript>
            <h2>Please enable javascript! (sorry)</h2>
         </noscript>
         <form id="hpc">
            <div class="row">
               <div class="col-3">
                  <label for="hpc-system">HPC System:</label>
                  <select id="hpc-system" name="hpc-system">
                  </select>
               </div>
               <div class="col-3">
                  <label for="software">Software:</label>
                  <select id="software" name="software">
                     <option>Select a system first</option>
                  </select>
               </div>
               <div class="col-3">
                  <label for="atoms">No. of atoms to simulate:</label>
                  <input type="number" id="atoms" name="atoms" />
               </div>
               <div class="col-3">
                  <label for="ns">Simulation time (ns):</label>
                  <input type="number" id="ns" name="ns" />
               </div>
            </div>
         </form>
         <div id="results"></div>
      </div>
   </div>
   <p><small>Values given by this tool are approximate. Values are calculated from are calculated from the <a href="https://github.com/HECBioSim/benchmark-results">HECBioSim benchmark results</a>. Storage requirements assume that trajectories are being stored in DCD/HDF5/NetCDF format with one frame per picosecond of simulation time. UK home power usage is based on <a href="https://www.ofgem.gov.uk/decision/decision-typical-domestic-consumption-values-2023">values from OFGEM in 2023</a>. For more detailed information on performance or the benchmark methodolgy, see <a href="#">Engineering Supercomputing Platforms for Biomolecular Applications</a>.</p>
   </small>
</div>


<script>
// this is temporary until it can be hosted somewhere else
const data = {
    "fits": [
        {
            "Machine": "ARCHER2",
            "program": "OpenMM",
            "nodata": "No data is available for OpenMM on ARCHER2. As OpenMM only provides a reference implementation on CPU, using it on CPU-based systems is not recommended."
        },
        {
            "Machine": "LUMI-G",
            "program": "OpenMM",
            "nodata": "No data is available for OpenMM on LUMI-G. OpenMM theoretically supports AMD via the openmm-hip plugin, however this plugin was not compatible with the ROCm version supplied on LUMI-G and the OpenMM version required to run the benchmark. As ROCm support on OpenMM is not official, please proceed with caution!"
        },
        {
            "fits": {
                "ns/day": [
                    0.2321842098046714,
                    -3.958692467769741,
                    21.28573665881839,
                    -34.648453742326154
                ],
                "J/ns": [
                    2.4224148585697327e-13,
                    -1.067845009666298e-06,
                    5.150362877852942,
                    87488.29044931043
                ]
            },
            "Machine": "JADE2",
            "program": "NAMD",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.05622610922783348,
                    -0.993176382194089,
                    4.734204437814949,
                    -3.897022719970984
                ],
                "J/ns": [
                    1.4661249691519497e-13,
                    -4.68212445778259e-07,
                    3.526581172663616,
                    63987.462888503884
                ]
            },
            "Machine": "JADE2",
            "program": "OpenMM",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.18093119857181153,
                    -2.9566193916464902,
                    14.984814443077978,
                    -21.67469099448406
                ],
                "J/ns": [
                    2.0141107940772611e-13,
                    -1.2222982576664515e-06,
                    3.9107451815513192,
                    50595.512691157
                ]
            },
            "Machine": "JADE2",
            "program": "GROMACS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.1335094863947238,
                    -2.31394672096245,
                    12.251956799916995,
                    -17.949803507826783
                ],
                "J/ns": [
                2.695029544215566e-13,
                -9.707085606730863e-07,
                2.821626286284685,
                19148.76348340309
                ]
            },
            "Machine": "JADE2",
            "program": "AMBER",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.1430633121597235,
                    -2.2361104280599804,
                    10.548661277258454,
                    -14.020085005853522
                ],
                "J/ns": [
                    -1.552654497897181e-11,
                    1.9021666704721093e-05,
                    45.005530595467754,
                    -100867.5223552831
                ]
            },
            "Machine": "JADE2",
            "program": "LAMMPS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.06075753444948685,
                    -1.1493964453638958,
                    6.208513274071868,
                    -7.668214842984452
                ],
                "J/ns": [
                    1.2200048295051207e-13,
                    -4.610964696898394e-07,
                    3.7650435346155655,
                    51051.463900313895
                ]
            },
            "Machine": "Grace Hopper Testbed",
            "program": "namd2",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.037699342999648645,
                    -0.7339509793131569,
                    3.6875109295934547,
                    -2.3862662433105695
                ],
                "J/ns": [
                    8.608869166748431e-14,
                    -1.9370662183374523e-07,
                    1.9038595348896805,
                    6980.72254456501
                ]
            },
            "Machine": "Grace Hopper Testbed",
            "program": "OpenMM",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.08241540734284215,
                    -1.4578676883906125,
                    7.587920475291541,
                    -9.228037114480879
                ],
                "J/ns": [
                    6.395446180540534e-14,
                    -2.400288722443302e-07,
                    1.2824795401988442,
                    9076.81859195345
                ]
            },
            "Machine": "Grace Hopper Testbed",
            "program": "GROMACS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.037803558593839826,
                    -0.8161455801622212,
                    4.748577069641206,
                    -5.5665844393572534
                ],
                "J/ns": [
                    1.3307745662829843e-13,
                    -3.880365894090523e-07,
                    1.3055927817825166,
                    5999.34696809314
                ]
            },
            "Machine": "Grace Hopper Testbed",
            "program": "AMBER",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    -0.45990950860135715,
                    7.106462232094406,
                    -36.922708095637994,
                    65.67518974779578
                ],
                "J/ns": [
                    5.3252168181023606e-12,
                    -1.1580135848622176e-05,
                    13.21374253840508,
                    93711.55017597639
                ]
            },
            "Machine": "Grace Hopper Testbed",
            "program": "LAMMPS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.09443259038865728,
                    -1.724749488435469,
                    9.366118589861747,
                    -13.598831986985353
                ],
                "J/ns": [
                    -1.1760700594785254e-12,
                    7.64518228171803e-06,
                    5.103632472811936,
                    490158.09742213954
                ]
            },
            "Machine": "LUMI-G",
            "program": "namd2",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.07553309435729133,
                    -1.3906328276305653,
                    7.431033411848891,
                    -9.642150225961036
                ],
                "J/ns": [
                    8.863167177044271e-13,
                    -2.025949964622618e-06,
                    8.473161630967741,
                    -39702.98016479444
                ]
            },
            "Machine": "LUMI-G",
            "program": "GROMACS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.11434735750165863,
                    -2.0529702638164857,
                    11.270610916371826,
                    -17.118970054958165
                ],
                "J/ns": [
                    8.851622980942304e-13,
                    -2.375543852861337e-06,
                    5.708391446982169,
                    167220.24340010103
                ]
            },
            "Machine": "LUMI-G",
            "program": "AMBER",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.3131079862502913,
                    -5.2550838497501635,
                    28.189281181118666,
                    -47.90767249798419
                ],
                "J/ns": [
                    -1.3907590548341017e-11,
                    0.00012124475556964306,
                    14.062270094621939,
                    6213212.840357346
                ]
            },
            "Machine": "LUMI-G",
            "program": "LAMMPS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.035464719874874345,
                    -0.6070951193084679,
                    2.467710246501045,
                    -0.2543669938341861
                ],
                "J/ns": [
                    6.124889889876866e-13,
                    -3.221108426185746e-06,
                    24.726512053564733,
                    76687.83942162119
                ]
            },
            "Machine": "ARCHER2",
            "Best": "yes",
            "nodes": "1",
            "program": "namd2",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.06004142647407863,
                    -0.9667730776567985,
                    4.158352650738805,
                    -2.2324748171064894
                ],
                "J/ns": [
                    5.907447310773613e-13,
                    -2.4676671433283257e-06,
                    8.485030814052907,
                    -3089.3242564433967
                ]
            },
            "Machine": "ARCHER2",
            "Best": "yes",
            "nodes": "1",
            "program": "GROMACS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "fits": {
                "ns/day": [
                    0.09175068227674428,
                    -1.5045715144531633,
                    7.1109466588856485,
                    -8.0011053610172
                ],
                "J/ns": [
                    -6.92351355790611e-14,
                    -2.8292877384427353e-07,
                    24.268963806557597,
                    -95673.3469198658
                ]
            },
            "Machine": "ARCHER2",
            "Best": "yes",
            "nodes": "1",
            "program": "AMBER",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            },
            "message": "Warning: AMBER is primarily written for GPUs! It will run badly on CPU-based systems like ARCHER2."
        },
        {
            "fits": {
                "ns/day": [
                    0.0507500215231487,
                    -0.8358066754602022,
                    3.587379069449128,
                    -2.275763717317376
                ],
                "J/ns": [
                    -8.70029255069732e-12,
                    1.534661525325503e-05,
                    32.09114706041763,
                    285289.09414213157
                ]
            },
            "Machine": "ARCHER2",
            "Best": "yes",
            "nodes": "1",
            "program": "LAMMPS",
            "log": {
                "ns/day": true,
                "J/ns": false
            },
            "eqns": {
                "ns/day": "fit_poly",
                "J/ns": "fit_poly"
            }
        },
        {
            "maxnodes": {
                "fits": [
                    4.7236423706489425,
                    2.4264461444841586e-05,
                    1.4019275093766803
                ],
                "eqns": {
                    "nodes": "fit_log"
                },
                "Machine": "ARCHER2",
                "meta": "Max nodes"
            },
            "gbperatomperns": 1.9e-06,
            "info": "hardcoded",
            "kwhperj": 2.7777778e-07,
            "storagepergbpernsperatom": 1.9e-06,
            "ukavgkwhdar": 7.392197125256674,
            "html": {
                "row": "<div class=\"res-row $class\"><i class=\"fa $icon\"></i> $text</div>",
                "option": "<option value=\"$name\">$text</option>"
            },
            "atomwarning": "WARNING: your system contains $equality atoms than our $size test system. These results from this tool are probably wrong!",
            "texticons": {
                "node-hours": "fa-clock-o",
                "ns-day": "fa-tachometer",
                "storage-gb": " fa-hdd-o",
                "power-kwh": "fa-bolt",
                "house-days": "fa-home",
                "max-nodes": "fa-server",
                "message": "fa-warning"
            },
            "minmax": {
                "atoms": [
                    19000,
                    3000000
                ]
            },
            "textlabels": {
                "storage-gb": "$resultGB of storage",
                "ns-day": "$result ns/day",
                "power-kwh": "$result kWh of power",
                "house-days": "...which could power an average UK home for $result days",
                "node-hours": "$result node hours",
                "max-nodes": "1 node recommended for maximum efficiency",
                "message": "$result"
            }
        }
    ]
};

const HARDCODED = get_fit(data, {"info":"hardcoded"});
const KWH_PER_J = HARDCODED["kwhperj"];
const STORAGE_GB_NS_ATOM = HARDCODED["storagepergbpernsperatom"];
const UK_AVG_KWH_DAY = HARDCODED["ukavgkwhdar"];
const ROW = HARDCODED["html"]["row"];
const OPTION = HARDCODED["html"]["option"];
const TEXT_LABELS = HARDCODED["textlabels"];
const MIN_MAX = HARDCODED["minmax"];
const TEXT_ICONS = HARDCODED["texticons"];
const ATOM_WARNING = HARDCODED["atomwarning"];
//https://www.ofgem.gov.uk/decision/decision-typical-domestic-consumption-values-2023

// for some reason, enabling this makes functions inaccessible through window
//const pubs = await fetch("https://hecbiosim.github.io/com_hecbiosim_impact/pubs.json");
//const pubsdata = await pubs.json()

function is_subset(superObj, subObj) {
    return Object.keys(subObj).every((ele) => {
        if (typeof subObj[ele] == "object") {
            return isSubset(superObj[ele], subObj[ele]);
        }
        return subObj[ele] === superObj[ele];
    });
}

function get_fit(data, match) {
    for (let fit_idx in data["fits"]) {
        let fit = data["fits"][fit_idx];
        if (is_subset(fit, match)) {
            return fit;
        }
    }
    return null;
}

function fit_exp(x, fitparams, log_transform) {
    let a = fitparams[0],
    b = fitparams[1];
    return a * Math.exp(b * x);
}

function fit_poly(x, fitparams) {
    let a = fitparams[0],
    b = fitparams[1],
    c = fitparams[2],
    d = fitparams[3];
    return a * x ** 3 + b * x ** 2 + c * x + d;
}

function fit_log(x, fitparams) {
    let a = fitparams[0],
    b = fitparams[1],
    c = fitparams[2];
    return a * Math.log(x * b + c);
}

function is_float(n) {
    return !Number.isNaN(parseFloat(n)) && Number.isFinite(n) && !Number.isInteger(n);
}

function get_options(data, option_field, prerequisites={}){
    let options = [];
    for (let fit_idx in data["fits"]){
        let fit = data["fits"][fit_idx];
        if (is_subset(fit, prerequisites) && fit.hasOwnProperty(option_field)){
            options.push(fit[option_field]);
        }
    }
    return Array.from(new Set(options));
}

function get_recommended(data, machine, ns, atoms, program, round = true, text=true) {
    let output = {};
    output["message"] = null;
    let fit = get_fit(data, { "Machine": machine, "program": program });

    if (fit === null){
        return {"message":"No matching benchmarks!"};
    }

    if (fit.hasOwnProperty("nodata")){
        output["message"] = fit["nodata"];
        return output;
    }

    if (fit["log"]["ns/day"]) {
        output["ns-day"] = 10**window[fit["eqns"]["ns/day"]](Math.log10(atoms), fit["fits"]["ns/day"]);
    } else {
        output["ns-day"] = window[fit["eqns"]["ns/day"]](atoms, fit["fits"]["ns/day"]);
    }
    let jns = window[fit["eqns"]["J/ns"]](atoms, fit["fits"]["J/ns"]);
    output["storage-gb"] = ns * atoms * STORAGE_GB_NS_ATOM;
    output["node-hours"] = (ns / output["ns-day"]) * 24;
    output["max-nodes"] = 1;
    output["power-kwh"] = jns * ns * KWH_PER_J;
    output["house-days"] = output["power-kwh"] / UK_AVG_KWH_DAY;

    //if (fit["Machine"] == "ARCHER2") {
    //    let hardcoded = get_fit(data, { info: "hardcoded" })["maxnodes"];
    //    maxnodes = window[hardcoded["eqns"]["nodes"]](atoms, hardcoded["fits"]);
    //    output["max-nodes"] = Math.round(maxnodes);
    //}

    if (fit.hasOwnProperty("message")) {
        output["message"] = fit["message"];
    }

    if (atoms > MIN_MAX["atoms"][1]){
        output["message"] = template(ATOM_WARNING, {"equality":"more", "size":"largest"});
    }

    if (atoms < MIN_MAX["atoms"][0]){
        output["message"] = template(ATOM_WARNING, {"equality":"fewer", "size":"smallest"});
    }

    if (round) {
        for (key in output) {
            if (is_float(output[key])) {
                output[key] = Number(output[key].toPrecision(3));
            }
        }
    }

    if (text){
        for (key in output){
            output[key] = template(TEXT_LABELS[key], {"result":output[key]});
        }
    }

    return output;
}

function template(template_literal, params) {
    for (let key in params){
        template_literal = template_literal.replace("$"+key, params[key])
    }
    return template_literal;
}

function make_combo_box_contents(items){
    let html = "";
    html += '<option value="" disabled selected>Select your option</option>'
    for (let item of items){
        html += template(OPTION, {"name":item, "text":item})+"\n";
    }
    return html;
}

function make_rows(results){
    let html = "";
    html += "<hr>"
    for (let key in results){
        if (results[key] != "null"){
            html += template(ROW, {"class":key, "text":results[key], "icon":TEXT_ICONS[key]})+"\n";
        }
    }
    return html;
}

// edit DOM
let combo_contents = make_combo_box_contents(get_options(data, "Machine"));
document.getElementById('hpc-system').innerHTML = combo_contents;
//update_software();
document.getElementById("software").disabled = true;


function update_software(){
    let value = document.getElementById("hpc-system").value
    let combo_contents = make_combo_box_contents(get_options(data, "program", {"Machine":value}));
    document.getElementById('software').innerHTML = combo_contents;
    document.getElementById("software").disabled = false;
    update_results();
}

// update results when you change stuff
document.getElementById("atoms").oninput = update_results;
document.getElementById("ns").oninput = update_results;
document.getElementById("hpc-system").oninput = update_software; // need to also update software here
document.getElementById("software").oninput = update_results;

function update_results(){
    let atoms = document.getElementById("atoms").value;
    let ns = document.getElementById("ns").value;
    let system = document.getElementById("hpc-system").value;
    let software = document.getElementById("software").value;
    if (atoms !== "" && ns !== "" && system !== null && software !== null){
        let recommended = get_recommended(data, system, ns, atoms, software);
        let results_contents = make_rows(recommended)
        document.getElementById('results').innerHTML = results_contents;
    }

}

</script>
