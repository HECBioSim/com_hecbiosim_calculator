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
        <h2> <?php echo $this->escape($this->params->get('page_heading')); ?> </h2>
    </div>
<?php endif; ?>

<link rel="stylesheet" href="media/com_hecbiosim_calculator/css/calculator.css">

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
    <p>Values given by this tool are approximate. Values are calculated from are calculated from the <a href="https://github.com/HECBioSim/benchmark-results">HECBioSim benchmark results</a>. Storage requirements assume that trajectories are being stored in DCD/HDF5/NetCDF format with one frame per picosecond of simulation time. UK home power usage is based on <a href="https://www.ofgem.gov.uk/decision/decision-typical-domestic-consumption-values-2023">values from OFGEM in 2023</a>. For more detailed information on performance or the benchmark methodolgy, see <a href="https://arxiv.org/pdf/2506.15585">Engineering Supercomputing Platforms for Biomolecular Applications</a>.</p>
</div>

<script type="module" src="media/com_hecbiosim_calculator/js/calculator.js" type="text/javascript"></script>
