<?php
class LmdCompiler extends CApplicationComponent
{
	/**
	 * @var string absolute path to the nodejs executable.
	 */
	public $nodePath;
	/**
	 * @var string absolute path to the compiler.
	 */
	public $compilerPath;

	public $packages=array();
	/**
	 * @var boolean whether to always compile files, even if they have not changed.
	 */
	public $forceCompile = false;

	/**
	 * Initializes the component.
	 * @throws CException if initialization fails.
	 */
	public function init()
	{
		parent::init();
	}

	private function buildFile($package, $output, $version=null)
	{
		$command = $this->nodePath . ' ' . $this->compilerPath . ' build '.$package.' --output="'.$output.'"';
		if ($version !== null) {
			$command .= ' --version="'.$version.'"';
		}
		$command .= ' > /dev/null';

		$return = 0;
		$output = array();
		@exec($command, $output, $return);

		if ($return !== 0)
			throw new CException('Failed to build lmd file "' . $package . '" using command: ' . $command);
	}

	public function registerLmdFile($package, $version=null)
	{
		if ( !isset($this->packages[$package])) {
			throw new CException(500);
		}
		$file = $this->packages[$package];

		$basePath = Yii::getPathOfAlias('webroot');
		$fullName = $basePath .'/'.$file;

		if (!file_exists($fullName) || $this->forceCompile) {
			$this->buildFile($package, $file, $version);
		}


		if ($version !== null) {
			$htmlOptions = array(
				'data-src'=>'/'.$file,
				'data-key'=>'lmd',
				'id'=>'lmd-initializer',
				'data-version' => $version,
			);
			$script = '!function(a,b,c,d,e,f){var n,o,p,q,r,s,t,g=function(b){return a.Function("return "+b)()},h=b.getElementById(c),i=h[e](d+"version"),j=h[e](d+"key"),k=h[e](d+"src"),l=a.localStorage,m=new RegExp("^"+j+":([^:]+):(.*)$");if(l&&(q=l[j])){try{o=a.JSON.parse(q),o&&o.options.version===i&&(r=g(o.main),s=g(o.lmd));for(j in l)t=j.match(m),t&&(t[1]===i?o.modules[t[2]]=a.JSON.parse(l[j]):l[f](j))}catch(u){}if(s&&r)return s(a,r,o.modules,o.modules_options,o.options),void 0;l[f](j);for(j in l)m.test(j)&&l[f](j)}p=b.getElementsByTagName("head")[0],n=b.createElement("script"),n.setAttribute("src",k),p.insertBefore(n,p.firstChild)}(this,this.document,"lmd-initializer","data-","getAttribute","removeItem");';
			Yii::app()->clientScript->registerScript('lmd-initializer', $script, CClientScript::POS_END, $htmlOptions);
		} else {
			Yii::app()->clientScript->registerScriptFile('/'.$file, CClientScript::POS_END);
		}
	}




}
