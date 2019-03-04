<?php
namespace Yeti\Main\Building\Exports\Utilities;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Collection;

use \Able\IO\Path;
use \Able\IO\Directory;

use \Able\Helpers\Arr;
use \Able\Helpers\Src;

class Export {

	/**
	 * @var Builder
	 */
	private $Builder = null;

	/**
	 * Export constructor.
	 * @param Builder $Builder
	 */
	public final function __construct(Builder $Builder) {
		$this->Builder = $Builder;
	}

	/**
	 * @const string
	 */
	public const AT_ONE = 'one';

	/**
	 * @const string
	 */
	public const AT_SET = 'set';

	/**
	 * @const string
	 */
	public const AT_PAGE = 'page';

	/**
	 * @const string
	 */
	public const AT_LIST = 'list';

	/**
	 * @param Directory $Destination
	 * @param string $unit
	 * @param string $value
	 * @throws \Exception
	 */
	public final function save(Directory $Destination, string $unit, string $value): void {
		if (!in_array($unit, [self::AT_ONE, self::AT_SET, self::AT_PAGE, self::AT_LIST])) {
			throw new \Exception(sprintf('Undefined access type: %s!', $unit));
		}

		if (!$Destination->isEmpty()) {
			throw new \Exception(sprintf('Target directory is not empty: %s', $Destination));
		}

		foreach ($this->{'scale' . Src::tcm($unit)}(Arr::unpack(preg_split('/\s*,+\s*/',
			$value, -1, PREG_SPLIT_NO_EMPTY), ':')) as $file => $content) {
				Path::create($Destination, $file)->forceFile()->rewrite($content);
		}
	}

	/**
	 * @param array $Options
	 * @return \Generator
	 * @throws \Exception
	 */
	private final function scaleOne(array $Options = []): \Generator {
		if (!isset($Options['%attr'])) {
			throw new \Exception('Invalid or empty attribute name!');
		}

		foreach ($this->Builder->get() as $Item) {
			if (!empty($Item->{$Options['%attr']})) {
				yield 'attr' . md5($Item->{$Options['%attr']}) . '.data' => base64_encode(json_encode($Item));
			}
		}

		yield 'data.php' => '<?php return isset($key) && file_exists($file = __DIR__ . "/attr" . md5($key) . ".data")'
			. ' ? json_decode(base64_decode(file_get_contents($file))) : [];?>';
	}

	/**
	 * @param array $Options
	 * @return \Generator
	 * @throws \Exception
	 */
	private final function scalePage(array $Options = []): \Generator {
		if (!isset($Options['%size'])
			|| !is_numeric($Options['%size']) || (int)$Options['%size'] < 1) {
				throw new \Exception('Invalid size!');
		}

		$Chunks = $this->Builder->get()->chunk($Options['%size']);
		if (isset($Options['%order']) && $Options['%order'] == 'desc'){
			$Chunks = $Chunks->reverse();
		}

		foreach ($Chunks as $index => $Collection){
			yield 'page' . (++$index) . '.data'
				=> base64_encode(json_encode($Collection->toArray()));
		}

		yield 'data.php' => '<?php return isset($key) && file_exists($file = __DIR__ . "/page" . (int)$key . ".data")'
			. ' ? json_decode(base64_decode(file_get_contents($file))) : [];?>';
	}

	/**
	 * @param array $Options
	 * @return \Generator
	 * @throws \Exception
	 */
	private final function scaleSet(array $Options = []): \Generator {
		if (!isset($Options['%attr'])) {
			throw new \Exception('Invalid or empty attribute name!');
		}

		$Values = array_filter(array_unique($this->Builder->get()->pluck($Options['%attr'])->toArray()));
		sort($Values);

		foreach ($Values as $value) {
			$Chunks = $this->Builder->where($Options['%attr'], '=', $value)->get();

			if (isset($Options['%order']) && $Options['%order'] == 'desc'){
				$Chunks = $Chunks->reverse();
			}

			if (isset($Options['%limit'])){
				if (!is_numeric($Options['%limit']) || (int)$Options['%limit'] < 1){
					throw new \Exception('Limit can not be less than zero!');
				}

				$Chunks = $Chunks->take($Options['%limit']);
			}

			yield 'set' . md5($value) . '.data'
				=> base64_encode(json_encode($Chunks->toArray()));

		}

		yield 'data.php' => '<?php return isset($key) && file_exists($file = __DIR__ . "/set" . md5($key) . ".data")'
			. ' ? json_decode(base64_decode(file_get_contents($file))) : [];?>';
	}

	/**
	 * @param array $Options
	 * @return \Generator
	 * @throws \Exception
	 */
	private final function scaleList(array $Options = []): \Generator {
		$List = $this->Builder->get();

		if (isset($Options['%order']) && $Options['%order'] == 'desc'){
			$List = $List->reverse();
		}

		yield 'list.data'
			=> base64_encode(json_encode($List->toArray()));

		yield 'data.php' => '<?php return file_exists($file = __DIR__ . "/list.data")'
			. ' ? json_decode(base64_decode(file_get_contents($file))) : [];?>';
	}

	/**
	 * @return mixed
	 */
	public final function count(){
		return $this->Builder->count();
	}
}

