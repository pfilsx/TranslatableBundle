<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?php if ($api_resource): ?>use ApiPlatform\Core\Annotation\ApiResource;
<?php endif ?>
use Doctrine\ORM\Mapping as ORM;
use Pfilsx\Translatable\Contract\TranslationInterface;
use Pfilsx\Translatable\Entity\TranslationTrait;

/**
<?php if ($api_resource): ?> * @ApiResource()
<?php endif ?>
* @ORM\Entity(repositoryClass="<?= $repository_full_class_name ?>")
*/
class <?= $class_name." implements TranslationInterface\n" ?>
{
    use TranslationTrait;

    /**
    * @ORM\Id()
    * @ORM\GeneratedValue()
    * @ORM\Column(type="integer")
    */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
