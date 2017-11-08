<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Process;

use Candango\MyFuses\Exceptions\CircuitException;

/**
 * FuseQueue - FuseQueue.php
 *
 * This is the Fuse Queue. It is a series of queues that holds the actions
 * that should be executed during the request.
 *
 * @category   controller
 * @package    myfuses.process
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      989a54b1289e18fc8ab43f4d035c3dba11e4661a
 */
class FuseQueue
{
    private $preFuseActionQueue = array();

    private $processQueue = array();

    private $postFuseactionQueue = array();

    private $preProcessQueue = array();

    private $postProcessQueue = array();

    /**
     * Queue request
     * 
     * @var FuseRequest
     */
    private $request;

    public function __construct(FuseRequest &$request)
    {
        $this->request = &$request;

        $this->buildPreProcessQueue();

        $this->buildProcessQueue();

        $this->buildPostProcessQueue();
    }

    private function buildProcessQueue()
    {
        $action = $this->request->getAction();

	    if ($action->getCircuit()->getAccess() == Circuit::INTERNAL_ACCESS) {
            $params = array(
                'circuitName' => $action->getCircuit()->getName(),
                'application' => $action->getCircuit()->getApplication()
            );
            throw new CircuitException($params,
                CircuitException::USER_TRYING_ACCESS_INTERNAL_CIRCUIT);
	    }

        $this->processQueue[] = $this->request->getAction();
    }

    private function buildPreProcessQueue()
    {
        // NOTE: I don't know about this fixme, but let's see it's needed
        // FIXME: Plugin::PRE_PROCESS_PHASE must be changed to
        // FIXME: MyFusesLifecycle::PRE_PROCESS_PHASE
        $queue = array();

        // gettin all circuit prefuseactions possible
        $circuit = $this->request->getAction()->getCircuit();

        while (!is_null($circuit)) {
            if (!is_null($circuit->getPreFuseAction())) {
                array_unshift($queue, $circuit->getPreFuseAction());
            }
            $circuit = $circuit->getParent();
        }

        // getting the prefuseaction
        array_unshift($queue, $this->request->getApplication()->
            getCircuit("MYFUSES_GLOBAL_CIRCUIT")->
            getAction("PreProcessFuseAction"));

        $this->preProcessQueue = $queue;
    }

    private function buildPostProcessQueue()
    {
        $queue = array();

        // getting all possible circuit prefuseactions
        $circuit = $this->request->getAction()->getCircuit();

        while (!is_null($circuit)) {
            if(!is_null($circuit->getPostFuseAction())) {
                $queue[] = $circuit->getPostFuseAction();
            }
            $circuit = $circuit->getParent();
        }

        // getting THE post fuseaction
        $queue[] = $this->request->getApplication()->
            getCircuit("MYFUSES_GLOBAL_CIRCUIT")->
            getAction("PostProcessFuseAction");

        $this->postProcessQueue = $queue;
    }

    public function getProcessQueue()
    {
        return $this->processQueue;
    }

    public function getPreProcessQueue()
    {
        return $this->preProcessQueue;
    }

    public function getPostProcessQueue()
    {
        return $this->postProcessQueue;
    }

    public function getPreFuseActionQueue()
    {
        return $this->preFuseActionQueue;
    }

    public function getPostFuseActionQueue()
    {
        return $this->postFuseactionQueue;
    }
}
