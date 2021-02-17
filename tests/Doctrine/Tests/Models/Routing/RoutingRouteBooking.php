<?php

declare(strict_types=1);

namespace Doctrine\Tests\Models\Routing;

/**
 * @Entity
 */
class RoutingRouteBooking
{
    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @var RoutingRoute
     * @ManyToOne(targetEntity="RoutingRoute", inversedBy="bookings")
     * @JoinColumn(name="route_id", referencedColumnName="id")
     */
    public $route;

    /**
     * @var string
     * @Column(type="string")
     */
    public $passengerName = null;

    public function getPassengerName()
    {
        return $this->passengerName;
    }
}
