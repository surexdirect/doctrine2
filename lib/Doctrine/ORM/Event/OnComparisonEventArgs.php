<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ORM\Event;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OnComparisonEventArgs.
 *
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class OnComparisonEventArgs extends LifecycleEventArgs
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var mixed
     */
    private $originalValue;

    /**
     * @var mixed
     */
    private $actualValue;

    /**
     * Returns a negative integer, zero, or a positive integer as the actualValue is less than, equal to,
     * or greater than the specified originalValue.
     * @var int
     */
    private $comparisonResult;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em
     * @param object                 $entity
     * @param string                 $propertyName
     * @param mixed                  $originalValue
     * @param mixed                  $actualValue
     */
    public function __construct(
        EntityManagerInterface $em,
        $entity,
        string $propertyName,
        $originalValue,
        $actualValue
    ) {
        parent::__construct($entity, $em);
        $this->propertyName  = $propertyName;
        $this->originalValue = $originalValue;
        $this->actualValue   = $actualValue;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    /**
     * @return mixed
     */
    public function getActualValue()
    {
        return $this->actualValue;
    }

    /**
     * @return int|null
     */
    public function getComparisonResult()
    {
        return $this->comparisonResult;
    }

    /**
     * @param int $comparisonResult
     */
    public function setComparisonResult(int $comparisonResult)
    {
        $this->comparisonResult = $comparisonResult;
    }
}
