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

namespace Doctrine\Common\Collections\Expr;

use Doctrine\Common\Collections\Operation\Operation;
use Doctrine\Common\Collections\Operation\SetValue;

/**
 * An Expression visitor walks a graph of expressions and turns them into a
 * query for the underlying implementation.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
abstract class ExpressionVisitor
{
    /**
     * Converts a comparison expression into the target query language output.
     *
     * @param Comparison $comparison
     *
     * @return mixed
     */
    abstract public function walkComparison(Comparison $comparison);

    /**
     * Converts a value expression into the target query language part.
     *
     * @param Value $value
     *
     * @return mixed
     */
    abstract public function walkValue(Value $value);

    /**
     * Converts a composite expression into the target query language output.
     *
     * @param CompositeExpression $expr
     *
     * @return mixed
     */
    abstract public function walkCompositeExpression(CompositeExpression $expr);

    /**
     * Converts a composite expression into the target query language output.
     *
     * @param string   $field
     * @param SetValue $operation
     *
     * @return mixed
     */
    abstract public function walkSetValue($field, SetValue $operation);

    /**
     * Dispatches walking an expression to the appropriate handler.
     *
     * @param Expression $expr
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function dispatch(Expression $expr)
    {
        switch (true) {
            case ($expr instanceof Comparison):
                return $this->walkComparison($expr);

            case ($expr instanceof Value):
                return $this->walkValue($expr);

            case ($expr instanceof CompositeExpression):
                return $this->walkCompositeExpression($expr);

            default:
                throw new \RuntimeException("Unknown Expression " . get_class($expr));
        }
    }

    /**
     * Dispatches walking an expression to the appropriate handler.
     *
     * @param string    $field
     * @param Operation $operation
     *
     * @return \Closure
     *
     * @throws \RuntimeException
     */
    public function apply($field, Operation $operation)
    {
        switch (true) {
            case ($operation instanceof SetValue):
                return $this->walkSetValue($field, $operation);
                break;

            default:
                throw new \RuntimeException("Unknown operation " . get_class($operation));
        }
    }
}
