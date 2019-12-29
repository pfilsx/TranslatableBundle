<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?php if ($api_resource): ?>use ApiPlatform\Core\Annotation\ApiResource;
<?php endif ?>
use Doctrine\ORM\Mapping as ORM;
use Pfilsx\Translatable\Contract\TranslatableInterface;
use Pfilsx\Translatable\Entity\TranslatableTrait;

/**
<?php if ($api_resource): ?> * @ApiResource()
<?php endif ?>
* @ORM\Entity(repositoryClass="<?= $repository_full_class_name ?>")
*/
class <?= $class_name." implements TranslatableInterface\n" ?>
{
    use TranslatableTrait;

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
