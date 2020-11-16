package testFactory

import (
	"fmt"
	"github.com/mitchellh/cli"
)
type Test struct {

}

func TestFactory () (cli.Command , error) {
	fmt.Println("I am foo command")
	return new(Test),nil
}

func (*Test) Run(args []string) int {

	return 0
}

func (*Test) Help() string {

	return ""
}

func (*Test) Synopsis() string {

	return ""
}