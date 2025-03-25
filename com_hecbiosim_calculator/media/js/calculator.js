const data_request = await fetch("https://raw.githubusercontent.com/HECBioSim/benchmark-results/refs/heads/main/fits.json")
const data = await data_request.json()

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
        output["ns-day"] = 10**eval(fit["eqns"]["ns/day"])(Math.log10(atoms), fit["fits"]["ns/day"]);
    } else {
        output["ns-day"] = eval(fit["eqns"]["ns/day"])(atoms, fit["fits"]["ns/day"]);
    }
    let jns = eval(fit["eqns"]["J/ns"])(atoms, fit["fits"]["J/ns"]);
    output["storage-gb"] = ns * atoms * STORAGE_GB_NS_ATOM;
    output["node-hours"] = (ns / output["ns-day"]) * 24;
    output["max-nodes"] = 1;
    output["power-kwh"] = jns * ns * KWH_PER_J;
    output["house-days"] = output["power-kwh"] / UK_AVG_KWH_DAY;

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
        for (let key in output) {
            if (is_float(output[key])) {
                output[key] = Number(output[key].toPrecision(3));
            }
        }
    }

    if (text){
        for (let key in output){
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


//let recd = get_recommended(data, "JADE2", 5, 1000000, "GROMACS");
//console.log(recd);

//console.log(get_options(data, "Machine"))
//console.log(get_options(data, "program", {"Machine":"ARCHER2"} ))

//console.log(template("$ns ns/day (bad)", {"ns":12}));

//console.log(make_combo_box_contents(get_options(data, "Machine")));
//console.log(make_combo_box_contents(get_options(data, "program", {"Machine":"ARCHER2"} )));

//console.log(make_rows(recd));


// make it work

// populate form
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
