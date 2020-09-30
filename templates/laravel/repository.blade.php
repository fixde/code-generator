namespace App\Repositories;

use App\Models\{{ $model['STUDLY'] }};
use Fixde\CodeGenerator\Repositories\BaseRepository;

class {{ $model['STUDLY'] }}Repository extends BaseRepository
{
    /**
     * @return {{ $model['STUDLY'] }}
     */
    public function getModel()
    {
        return {{ $model['STUDLY'] }}::class;
    }
}
