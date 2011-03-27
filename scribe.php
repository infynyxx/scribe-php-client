<?php

$GLOBALS['THRIFT_ROOT'] = 'thrift';

// Load up all the thrift stuff
require $GLOBALS['THRIFT_ROOT'].'/Thrift.php';
require $GLOBALS['THRIFT_ROOT'].'/autoload.php';

// Load the package for scribe
require_once $GLOBALS['THRIFT_ROOT'].'/packages/scribe/scribe.php';


function create_scribe_client() {
  try {
    // Set up the socket connections
    $scribe_servers = array('localhost');
    $scribe_ports = array(1463);
    print "creating socket pool\n";
    $sock = new TSocketPool($scribe_servers, $scribe_ports);
    $sock->setDebug(0);
    $sock->setSendTimeout(1000);
    $sock->setRecvTimeout(2500);
    $sock->setNumRetries(1);
    $sock->setRandomize(false);
    $sock->setAlwaysTryLast(true);
    $trans = new TFramedTransport($sock);
    $prot = new TBinaryProtocol($trans);

    // Create the client
    print "creating scribe client\n";
    $scribe_client = new scribeClient($prot);

    // Open the transport (we rely on PHP to close it at script termination)
    print "opening transport\n";
    $trans->open();

  } catch (Exception $x) {
    print "Unable to create global scribe client, received exception: $x \n";
    return null;
  }

  return $scribe_client;
}

create_scribe_client();
