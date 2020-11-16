package main

import (
	"desk_awesome/cli/testFactory"
	"fmt"
	"github.com/sirupsen/logrus"
	"github.com/mitchellh/cli"
	"log"
	"os"
)

func main () {
	c := cli.NewCLI("app", "1.0.0")
	c.Args = os.Args[1:]
	c.Commands = map[string]cli.CommandFactory{
		"test": testFactory.TestFactory,
	}

	exitStatus, err := c.Run()
	if err != nil {
		log.Println(err)
	}

	os.Exit(exitStatus)
	logrus.Print("1113333")
	fmt.Println("111")
}