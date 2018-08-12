<?php
    namespace ps88\capeapi;


    use pocketmine\plugin\PluginBase;
    use pocketmine\utils\Utils;

    class CapeAPI extends PluginBase {

        public const FILE_PATH = 1;

        public const URL_PATH = 2;

        /** @var Cape[] */
        private $capes = [];

        /** @var CapeAPI */
        private static $instance;


        protected function onEnable() {
            self::$instance = $this;
            @mkdir($this->getDataFolder());
            $handle = opendir($this->getDataFolder());
            $files = [];
            while (false !== ($filename = readdir($handle))) {
                if ($filename == "." || $filename == "..")
                    continue;

                if (is_file($this->getDataFolder() . "/" . $filename))
                    $files[] = $filename;

            }
            closedir($handle);
            foreach ($files as $file)
                $this->capes[str_replace(".png", "", $file)] = $this->getCapeFromImage(str_replace(".png", "", $file), $this->getDataFolder() . $file);

            foreach ($this->getConfig()->getAll() as $name => $url)
                $this->capes[$name] = $this->getCapeFromImage($name, $url, self::URL_PATH);
        }

        /**
         * @param string $name
         * @return Cape
         */
        public function getCape(string $name): Cape{
            return $this->capes[$name] ?? null;
        }

        /**
         * @return Cape[]
         */
        public function getCapes(): array{
            return $this->capes;
        }

        /**
         * @param string $name
         * @param string $imagepath
         * @param int $type
         * @param int $a
         * @return Cape
         */
        private function getCapeFromImage(string $name, string $imagepath, int $type = self::FILE_PATH, int $a = 255): Cape {
            $image = ($type == self::FILE_PATH) ? @imagecreatefrompng($imagepath) : imagecreatefromstring(Utils::getURL($imagepath));
            $width = imagesx($image);
            $height = imagesy($image);
            $st = "";
            for ($y = 0; $y < $height; $y++)
                for ($x = 0; $x < $width; $x++) {
                    $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));
                    $r = $rgb['red'];
                    $g = $rgb['green'];
                    $b = $rgb['blue'];
                    $st .= chr($r) . chr($g) . chr($b) . chr($a);
                }
            return new Cape($name, $st);
        }

        /**
         * @return CapeAPI
         */
        public static function getInstance(): CapeAPI{
            return self::$instance;
        }
    }